<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // WAJIB TAMBAH INI BUAT HAPUS GAMBAR
use Carbon\Carbon;

class PeminjamanController extends Controller
{

    // Tambahkan ini di paling atas Model (di bawah class Peminjaman extends Model)
    // Supaya denda_realtime otomatis nempel saat data dipanggil

    public function index(Request $request)
    {
        // Tangkap inputan pencarian
        $search = $request->search;

        // Query Peminjaman dengan fitur Search (Nama User atau Judul Buku)
        $peminjamans = Peminjaman::with(['user', 'book'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('book', function ($q) use ($search) {
                        $q->where('title', 'like', '%' . $search . '%');
                    });
            })
            ->orderBy('created_at', 'desc') // Peminjaman baru selalu di atas
            ->paginate(7)
            ->withQueryString();

        return view('pages.backend.peminjaman.index', compact('peminjamans'));
    }

    public function approve($id)
    {
        $data = Peminjaman::findOrFail($id);
        $book = Book::find($data->book_id);

        if ($book && $book->stock > 0) {
            DB::transaction(function () use ($data, $book) {
                $data->update([
                    'status' => 'approve',
                    'tanggal_pinjam' => now(),
                    'jatuh_tempo' => now()->addDays(7),
                ]);

                $book->decrement('stock');
            });

            return redirect()->back()->with('success', 'Peminjaman disetujui!');
        }

        return redirect()->back()->with('error', 'Gagal! Stok buku habis.');
    }

    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status' => 'rejected']);
        return redirect()->back()->with('info', 'Peminjaman ditolak.');
    }

    // FUNGSI RETURN UNTUK PETUGAS (BACKEND) - ULTIMATE FIX
    public function returnBook(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // 1. JIKA BUKU DALAM STATUS VERIFIKASI PEMBAYARAN DENDA
        if ($peminjaman->status === 'verifikasi') {

            // JIKA ADMIN MENOLAK BUKTI
            if ($request->action === 'reject_payment') {
                if ($peminjaman->bukti_pembayaran) {
                    Storage::disk('public')->delete($peminjaman->bukti_pembayaran);
                }

                $peminjaman->update([
                    'status' => 'approve',
                    'tanggal_kembali' => null, // Argo denda diaktifkan lagi
                    'bukti_pembayaran' => null
                ]);

                return redirect()->back()->with('success', 'BERHASIL: Bukti pembayaran ditolak. Status dikembalikan ke Terlambat.');
            }

            // JIKA ADMIN MENERIMA BUKTI
            // Di PeminjamanController.php bagian returnBook (approve_payment)
            if ($request->action === 'approve_payment') {
                DB::transaction(function () use ($peminjaman) {
                    $peminjaman->update([
                        'status' => 'returned',
                        'total_denda' => $peminjaman->denda_realtime,
                        'is_printed' => false, // Set false agar tombol muncul di user
                    ]);
                    Book::find($peminjaman->book_id)->increment('stock');
                });

                return redirect()->back()->with('success', 'Pembayaran diverifikasi!');
            }
        }

        // 2. JIKA BUKU NORMAL (Admin manual klik Return / tanpa denda)
        if ($peminjaman->status === 'approve') {
            DB::transaction(function () use ($peminjaman) {
                $peminjaman->update([
                    'status' => 'returned',
                    'tanggal_kembali' => now(),
                ]);
                Book::find($peminjaman->book_id)->increment('stock');
            });

            return redirect()->back()->with('success', 'BERHASIL: Buku dikembalikan secara manual!');
        }

        return redirect()->back()->with('error', 'Gagal: Status buku tidak sesuai untuk aksi ini.');
    }

    public function show($id)
    {
        // Tarik data peminjaman beserta relasi user dan book-nya
        $peminjaman = Peminjaman::with(['user', 'book'])->findOrFail($id);

        return view('pages.backend.peminjaman.show', compact('peminjaman'));
    }
}
