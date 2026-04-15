<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function markAsPrinted($id)
    {
        $peminjaman = Peminjaman::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $peminjaman->update(['is_printed' => true]);
        return redirect()->route('peminjaman.print', $id);
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);

        // 1. Ambil transaksi yang AKTIF untuk buku ini
        $activePeminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->whereIn('status', ['pending', 'approve', 'verifikasi'])
            ->latest()
            ->first();

        // --- TAMBAHAN LOGIKA LIMIT & TELAT ---
        // Hitung total buku yang sedang dipinjam/proses (selain returned & rejected)
        $totalActiveCount = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approve', 'verifikasi'])
            ->count();

        // Hitung berapa banyak buku yang statusnya 'approve' tapi sudah melewati jatuh tempo
        $overdueCount = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'approve')
            ->get()
            ->filter(function ($item) {
                return Carbon::now()->startOfDay()->gt(Carbon::parse($item->jatuh_tempo)->startOfDay());
            })->count();
        // -------------------------------------

        // 2. Transaksi yang baru saja dikembalikan
        $lastReturned = Peminjaman::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->where('status', 'returned')
            ->where('is_printed', false)
            ->latest()
            ->first();

        // 3. Deklarasi variabel default
        $isPending = false;
        $isApproved = false;
        $isOverdue = false;
        $remainingDays = 0;
        $denda = 0;

        if ($activePeminjaman) {
            if ($activePeminjaman->status === 'pending') {
                $isPending = true;
            } elseif ($activePeminjaman->status === 'approve') {
                $isApproved = true;

                $jatuhTempo = Carbon::parse($activePeminjaman->jatuh_tempo)->startOfDay();
                $now = Carbon::now()->startOfDay();

                $remainingDays = abs($jatuhTempo->diffInDays($now));

                if ($now->gt($jatuhTempo)) {
                    $isOverdue = true;
                    $denda = abs($activePeminjaman->denda_realtime);
                }
            }
        }

        $peminjaman = $activePeminjaman;

        return view('pages.frontend.book.show', compact(
            'book',
            'activePeminjaman',
            'lastReturned',
            'peminjaman',
            'isPending',
            'isApproved',
            'isOverdue',
            'remainingDays',
            'denda',
            'totalActiveCount', // Kirim ke view
            'overdueCount'      // Kirim ke view
        ));
    }

    public function borrow(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        if ($book->stock <= 0) return redirect()->back()->with('error', 'Stok buku habis!');

        $alreadyPending = Peminjaman::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) return redirect()->back()->with('error', 'Permintaan masih diproses petugas.');

        Peminjaman::create([
            'user_id' => Auth::id(),
            'book_id' => $id,
            'jumlah' => 1,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Permintaan terkirim.');
    }

    public function returnBook(Request $request, $id)
    {
        // Cari yang statusnya masih 'approve' (artinya buku masih di tangan user)
        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->where('status', 'approve')
            ->first();

        // Jika sudah diklik kembalikan sebelumnya, cegah error "Data tidak ditemukan"
        if (!$peminjaman) {
            return redirect()->route('book.show', $id)->with('info', 'Proses pengembalian sudah dilakukan atau sedang diverifikasi.');
        }

        $jatuhTempo = Carbon::parse($peminjaman->jatuh_tempo)->startOfDay();
        $hariIni = Carbon::now()->startOfDay();

        // Pastikan denda positif
        $dendaAkhir = abs($peminjaman->denda_realtime);
        $isOverdue = $hariIni->gt($jatuhTempo) || $dendaAkhir > 0;

        if ($isOverdue) {
            $request->validate([
                'bukti_pembayaran' => 'required'
            ]);
        }

        try {
            DB::transaction(function () use ($peminjaman, $id, $request, $isOverdue, $dendaAkhir) {
                if ($isOverdue) {
                    $peminjaman->update([
                        'status' => 'verifikasi',
                        'tanggal_kembali' => now(),
                        'total_denda' => $dendaAkhir,
                        'bukti_pembayaran' => $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public'),
                    ]);
                } else {
                    // UNTUK YANG TEPAT WAKTU
                    $peminjaman->update([
                        'status' => 'returned',
                        'tanggal_kembali' => now(),
                        'total_denda' => 0,
                        'is_printed' => false, // Pastikan ini false agar tombol download muncul
                    ]);
                    Book::find($id)->increment('stock');
                }
            });

            $pesan = $isOverdue ? 'Bukti denda berhasil dikirim!' : 'Buku berhasil dikembalikan tepat waktu!';
            return redirect()->route('book.show', $id)->with('success', $pesan);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function print($id)
    {
        $peminjaman = Peminjaman::with(['user', 'book'])->findOrFail($id);

        // Refresh data agar total_denda yang baru diupdate terbaca
        $peminjaman->refresh();

        if ($peminjaman->user_id !== Auth::id() && !in_array(Auth::user()->role, ['admin', 'petugas', 'kepala_perpustakaan'])) {
            abort(403);
        }

        $pdf = Pdf::loadView('pages.frontend.book.print', compact('peminjaman'));
        return $pdf->download('Bukti_Pinjam_#' . $peminjaman->id . '.pdf');
    }
}
