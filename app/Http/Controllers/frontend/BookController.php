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

        // 1. Ambil transaksi yang AKTIF
        $activePeminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->whereIn('status', ['pending', 'approve', 'verifikasi'])
            ->latest()
            ->first();

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

                // Gunakan abs() agar hari tidak negatif
                $remainingDays = abs($jatuhTempo->diffInDays($now));

                if ($now->gt($jatuhTempo)) {
                    $isOverdue = true;
                    $denda = abs($activePeminjaman->denda_realtime); // Pastikan positif
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
            'denda'
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
        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->where('status', 'approve')
            ->first();

        if (!$peminjaman) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        $jatuhTempo = Carbon::parse($peminjaman->jatuh_tempo)->startOfDay();
        $hariIni = Carbon::now()->startOfDay();

        // PAKSA denda jadi positif menggunakan abs()
        $dendaAkhir = abs($peminjaman->denda_realtime);
        $isOverdue = $hariIni->gt($jatuhTempo) || $dendaAkhir > 0;

        if ($isOverdue) {
            $request->validate([
                'bukti_pembayaran' => 'required'
            ], [
                'bukti_pembayaran.required' => 'Buku terlambat, harap upload bukti pembayaran denda.',
                'bukti_pembayaran.max' => 'Ukuran file terlalu besar (Maks 2MB).'
            ]);
        }

        try {
            DB::transaction(function () use ($peminjaman, $id, $request, $isOverdue, $dendaAkhir) {
                if ($isOverdue) {
                    $peminjaman->update([
                        'status' => 'verifikasi',
                        'tanggal_kembali' => now(),
                        'total_denda' => $dendaAkhir, // Angka positif yang disimpan
                        'bukti_pembayaran' => $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public'),
                    ]);
                } else {
                    $peminjaman->update([
                        'status' => 'returned',
                        'tanggal_kembali' => now(),
                        'total_denda' => 0,
                    ]);
                    Book::find($id)->increment('stock');
                }
            });

            $pesan = $isOverdue ? 'Bukti denda Rp ' . number_format($dendaAkhir, 0, ',', '.') . ' terkirim!' : 'Buku kembali tepat waktu!';
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
