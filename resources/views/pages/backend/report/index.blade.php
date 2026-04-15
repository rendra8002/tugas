@extends('layouts.backend.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Laporan Perpustakaan</h1>
            </div>

            <div class="section-body">
                {{-- Filter --}}
                <div class="card" style="background-color: #1a1a24; border: 1px solid #333; border-radius: 12px;">
                    <div class="card-body">
                        <form action="{{ route('reports.index') }}" method="GET" class="row align-items-end">
                            {{-- 1. Filter Periode --}}
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="text-muted small font-weight-bold">PERIODE LAPORAN</label>
                                <select name="period" class="form-control"
                                    style="background-color: #252531; color: white; border: 1px solid #444;">
                                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini
                                    </option>
                                    <option value="weekly" {{ request('period') == 'weekly' ? 'selected' : '' }}>Minggu Ini
                                    </option>
                                    <option value="monthly" {{ request('period') == 'monthly' ? 'selected' : '' }}>Bulan Ini
                                    </option>
                                    <option value="yearly" {{ request('period') == 'yearly' ? 'selected' : '' }}>Tahun Ini
                                    </option>
                                </select>
                            </div>

                            {{-- 2. Filter Status Baru --}}
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="text-muted small font-weight-bold">STATUS PEMINJAMAN</label>
                                <select name="status" class="form-control"
                                    style="background-color: #252531; color: white; border: 1px solid #444;">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status
                                    </option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                        (Menunggu)</option>
                                    <option value="approve" {{ request('status') == 'approve' ? 'selected' : '' }}>Approve
                                        (Sedang Dipinjam)</option>
                                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>
                                        Returned (Selesai/Dikembalikan)</option>
                                    <option value="verifikasi" {{ request('status') == 'verifikasi' ? 'selected' : '' }}>
                                        Verifikasi (Pengecekan Denda)</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                        Rejected (Ditolak)</option>
                                </select>
                            </div>

                            {{-- 3. Tombol Aksi --}}
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary px-4"
                                    style="background-color: #5a67d8; border: none;">
                                    <i class="fas fa-filter mr-2"></i> Filter
                                </button>
                                {{-- Parameter status juga dikirim ke tombol Cetak PDF agar sinkron --}}
                                <a href="{{ route('reports.print-pdf', ['period' => request('period', 'today'), 'status' => request('status', 'all')]) }}"
                                    class="btn btn-danger px-4 ml-2">
                                    <i class="fas fa-file-pdf mr-2"></i> Cetak PDF
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tabel Riwayat Peminjaman & Pengembalian --}}
                <div class="card mt-4"
                    style="background-color: #1a1a24; color: white; border-radius: 12px; border: 1px solid #333;">
                    <div class="card-header border-bottom" style="border-color: #333 !important;">
                        <h4 style="color: white;">Riwayat Peminjaman & Pengembalian</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0" style="color: #ccc;">
                                <thead>
                                    <tr style="background-color: #252531; color: white;">
                                        <th>#</th>
                                        <th>Nama Peminjam</th>
                                        <th>Judul Buku</th>
                                        <th>Tgl Pinjam</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Tgl Kembali</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="text-white font-weight-bold">{{ $item->user->name }}</td>
                                            <td>{{ $item->book->title }}</td>
                                            <td>{{ $item->tanggal_pinjam ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>{{ $item->jatuh_tempo ? \Carbon\Carbon::parse($item->jatuh_tempo)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>
                                                {{-- FIX: Array Mapping Warna untuk Badge Status --}}
                                                @php
                                                    $badgeColors = [
                                                        'pending' => 'badge-warning', // Kuning
                                                        'approve' => 'badge-success', // Hijau
                                                        'returned' => 'badge-info', // Biru muda
                                                        'verifikasi' => 'badge-primary', // Biru tua
                                                        'rejected' => 'badge-danger', // Merah
                                                    ];
                                                    $colorClass = $badgeColors[$item->status] ?? 'badge-secondary';
                                                @endphp
                                                <span class="badge {{ $colorClass }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">Data tidak ditemukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Tabel Riwayat Denda --}}
                <div class="card mt-4"
                    style="background-color: #1a1a24; color: white; border-radius: 12px; border: 1px solid #333;">
                    <div class="card-header border-bottom" style="border-color: #333 !important;">
                        <h4 class="text-danger">Riwayat Denda</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0" style="color: #ccc;">
                                <thead>
                                    <tr style="background-color: #252531; color: white;">
                                        <th>#</th>
                                        <th>Nama Anggota</th>
                                        <th>Buku</th>
                                        <th>Total Denda</th>
                                        <th>Bukti Bayar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fines as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="text-white font-weight-bold">{{ $item->user->name }}</td>
                                            <td>{{ $item->book->title }}</td>
                                            <td class="text-warning font-weight-bold">Rp
                                                {{ number_format($item->total_denda, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($item->bukti_pembayaran)
                                                    <span class="text-success"><i class="fas fa-check-circle"></i>
                                                        Terlampir</span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td><span class="badge badge-danger">Lunas</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">Tidak ada riwayat denda.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
