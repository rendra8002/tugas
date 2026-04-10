<?php

namespace App\Http\Controllers\Backend; // Pastikan 'Backend', bukan 'Admin'

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'today');

        // Query dasar menggunakan tabel peminjamans
        $query = Peminjaman::with(['user', 'book']);

        // Filter Waktu berdasarkan tanggal_pinjam
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

        return view('pages.backend.report.index', compact('transactions', 'fines', 'period'));
    }

    public function printPdf(Request $request)
    {
        $period = $request->get('period', 'today');
        $query = Peminjaman::with(['user', 'book']);

        // Filter yang sama untuk PDF
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

        $pdf = Pdf::loadView('pages.backend.report.pdf', compact('transactions', 'fines', 'period'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-perpustakaan-' . $period . '.pdf');
    }
}
