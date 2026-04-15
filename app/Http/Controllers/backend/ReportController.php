<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tangkap parameter dari URL
        $period = $request->get('period', 'today');
        $status = $request->get('status', 'all'); // FIX: Tangkap filter status

        // Query dasar menggunakan tabel peminjamans
        $query = Peminjaman::with(['user', 'book']);

        // 2. LOGIKA FILTER STATUS
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // 3. LOGIKA FILTER WAKTU (Berdasarkan tanggal_pinjam)
        switch ($period) {
            case 'weekly':
                $query->whereBetween('tanggal_pinjam', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'monthly':
                $query->whereMonth('tanggal_pinjam', Carbon::now()->month)
                    ->whereYear('tanggal_pinjam', Carbon::now()->year);
                break;
            case 'yearly':
                $query->whereYear('tanggal_pinjam', Carbon::now()->year);
                break;
            case 'today':
            default:
                $query->whereDate('tanggal_pinjam', Carbon::today());
                break;
        }

        // Ambil semua data sesuai filter untuk tabel riwayat
        $allData = $query->latest()->get();

        // Pisahkan data untuk tabel denda (yang punya denda > 0)
        $transactions = $allData;
        $fines = $allData->where('total_denda', '>', 0);

        // FIX: Pastikan variabel $status ikut dikirim ke view menggunakan compact
        return view('pages.backend.report.index', compact('transactions', 'fines', 'period', 'status'));
    }

    public function printPdf(Request $request)
    {
        // 1. Tangkap parameter dari URL
        $period = $request->get('period', 'today');
        $status = $request->get('status', 'all'); // FIX: Tangkap filter status untuk PDF

        $query = Peminjaman::with(['user', 'book']);

        // 2. LOGIKA FILTER STATUS (Sama seperti index)
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // 3. LOGIKA FILTER WAKTU
        if ($period == 'weekly') {
            $query->whereBetween('tanggal_pinjam', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period == 'monthly') {
            $query->whereMonth('tanggal_pinjam', Carbon::now()->month)->whereYear('tanggal_pinjam', Carbon::now()->year);
        } elseif ($period == 'yearly') {
            $query->whereYear('tanggal_pinjam', Carbon::now()->year);
        } else {
            $query->whereDate('tanggal_pinjam', Carbon::today());
        }

        $allData = $query->latest()->get();
        $transactions = $allData;
        $fines = $allData->where('total_denda', '>', 0);

        // FIX: Pastikan variabel $status ikut dikirim ke view PDF
        $pdf = Pdf::loadView('pages.backend.report.pdf', compact('transactions', 'fines', 'period', 'status'))
            ->setPaper('a4', 'landscape');

        // Nama file PDF dibuat lebih dinamis sesuai filter
        return $pdf->download('laporan-perpustakaan-' . $period . '-' . $status . '.pdf');
    }
}
