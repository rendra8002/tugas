<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HeroController extends Controller
{
    public function index()
    {
        // === STAT CARDS ===
        $totalAnggota = User::where('role', 'anggota')->count();
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalDenda = Peminjaman::selectRaw('SUM(ABS(total_denda)) as total')->value('total') ?? 0;
        $bukuHampirHabis = Book::where('stock', '<=', 5)->count();

        // === LINE CHART — aktivitas 7 hari terakhir ===
        $labels = [];
        $dataDipinjam = [];
        $dataDikembalikan = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->translatedFormat('D'); // Sen, Sel, dst (pastikan locale id sudah diset)

            // Dipinjam = approve di tanggal tsb
            $dataDipinjam[] = Peminjaman::whereDate('tanggal_pinjam', $date)
                ->whereIn('status', ['approve', 'returned'])
                ->count();

            // Dikembalikan = returned di tanggal tsb
            $dataDikembalikan[] = Peminjaman::whereDate('tanggal_kembali', $date)
                ->where('status', 'returned')
                ->count();
        }

        $chartLabels = json_encode($labels);
        $chartDipinjam = json_encode($dataDipinjam);
        $chartDikembalikan = json_encode($dataDikembalikan);

        // === DONUT CHART — status bulan ini ===
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        $totalBulanIni = Peminjaman::whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->count();

        $jumlahDipinjam   = Peminjaman::whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->whereIn('status', ['approve'])->count();
        $jumlahDikembalikan = Peminjaman::whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->where('status', 'returned')->count();
        $jumlahTerlambat  = Peminjaman::whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)
            ->where('status', 'approve')
            ->whereDate('jatuh_tempo', '<', Carbon::today())
            ->count();

        // Hitung persentase (hindari division by zero)
        $pctDipinjam     = $totalBulanIni > 0 ? round(($jumlahDipinjam / $totalBulanIni) * 100) : 0;
        $pctDikembalikan = $totalBulanIni > 0 ? round(($jumlahDikembalikan / $totalBulanIni) * 100) : 0;
        $pctTerlambat    = $totalBulanIni > 0 ? round(($jumlahTerlambat / $totalBulanIni) * 100) : 0;

        $donutData = json_encode([$pctDipinjam, $pctDikembalikan, $pctTerlambat]);

        // === TABEL — 5 peminjaman terbaru ===
        $peminjamanterbaru = Peminjaman::with(['user', 'book'])
            ->latest()
            ->take(5)
            ->get();

        return view('pages.backend.hero.index', compact(
            'totalAnggota',
            'totalPetugas',
            'totalDenda',
            'bukuHampirHabis',
            'chartLabels',
            'chartDipinjam',
            'chartDikembalikan',
            'donutData',
            'pctDipinjam',
            'pctDikembalikan',
            'pctTerlambat',
            'peminjamanterbaru'
        ));
    }
}
