@extends('layouts.backend.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Peminjaman List</h1>
            </div>

            <div class="section-body">
                <style>
                    .badge-success {
                        box-shadow: 0 0 8px rgba(40, 167, 69, 0.4);
                        border: 1px solid rgba(255, 255, 255, 0.1);
                    }

                    .badge-verifikasi {
                        background-color: #17a2b8;
                        color: #fff;
                    }

                    /* Style lama lo tetep aman */
                    .table-dark-custom {
                        background-color: #1a1a24;
                        color: white;
                        border-radius: 8px;
                        overflow: hidden;
                    }

                    .table-dark-custom thead th {
                        border-bottom: 1px solid #333;
                        color: #aaa;
                        text-transform: uppercase;
                        font-size: 10px;
                        letter-spacing: 1px;
                        padding: 15px;
                    }

                    .table-dark-custom td {
                        border-top: 1px solid #222;
                        vertical-align: middle;
                        font-size: 13px;
                        padding: 15px;
                    }

                    .badge-custom {
                        padding: 5px 12px;
                        border-radius: 4px;
                        font-weight: 600;
                        font-size: 10px;
                    }

                    .badge-pending {
                        background-color: #ffc107;
                        color: #000;
                    }

                    .badge-approve {
                        background-color: #28a745;
                        color: #fff;
                    }

                    .badge-rejected {
                        background-color: #dc3545;
                        color: #fff;
                    }

                    .badge-returned {
                        background-color: #6c757d;
                        color: #fff;
                    }

                    .text-denda {
                        color: #ff4d4d;
                        font-weight: bold;
                        background: rgba(255, 77, 77, 0.1);
                        padding: 2px 8px;
                        border-radius: 4px;
                    }

                    .pagination .page-link {
                        background-color: #1a1a24;
                        border-color: #333;
                        color: #aaa;
                    }

                    .pagination .page-item.active .page-link {
                        background-color: #6F4E37;
                        border-color: #6F4E37;
                        color: white;
                    }

                    .pagination .page-link:hover {
                        background-color: #252531;
                        color: white;
                    }
                </style>

                <div class="card bg-transparent border-0">
                    {{-- Form Pencarian --}}
                    <div class="card-header bg-transparent border-0 px-0 d-flex justify-content-end">
                        <form action="{{ route('peminjaman.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control"
                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                    placeholder="Search User or Book..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"
                                        style="background-color: #6F4E37; border: none;">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark-custom mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Buku</th>
                                        <th>Tgl Pinjam</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Tgl Kembali</th>
                                        <th>Denda</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($peminjamans as $index => $pj)
                                        <tr>
                                            <td>{{ $peminjamans->firstItem() + $index }}</td>
                                            <td class="font-weight-600">{{ $pj->user->name }}</td>
                                            <td>{{ $pj->book->title }}</td>

                                            {{-- Tanggal Pinjam --}}
                                            <td>{{ $pj->tanggal_pinjam ? \Carbon\Carbon::parse($pj->tanggal_pinjam)->format('d M Y') : '-' }}
                                            </td>

                                            {{-- Jatuh Tempo --}}
                                            <td>
                                                <span
                                                    class="{{ $pj->status == 'approve' && \Carbon\Carbon::now()->gt($pj->jatuh_tempo) ? 'text-warning' : '' }}">
                                                    {{ $pj->jatuh_tempo ? \Carbon\Carbon::parse($pj->jatuh_tempo)->format('d M Y') : '-' }}
                                                </span>
                                            </td>

                                            {{-- Tanggal Kembali --}}
                                            <td>
                                                @if ($pj->status == 'returned' && $pj->tanggal_kembali)
                                                    <span class="text-success">
                                                        {{ \Carbon\Carbon::parse($pj->tanggal_kembali)->format('d M Y') }}
                                                    </span>
                                                @elseif ($pj->status == 'approve')
                                                    <span class="text-muted small">In Use</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            {{-- KOLOM DENDA --}}
                                            <td class="text-nowrap"> {{-- Tambahkan class text-nowrap di sini --}}
                                                @if ($pj->status == 'returned')
                                                    @if ($pj->total_denda > 0)
                                                        <span class="badge badge-success"
                                                            style="font-size: 10px; background-color: #28a745; color: white; white-space: nowrap;">
                                                            <i class="fas fa-check-double mr-1"></i> LUNAS
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                @else
                                                    @if ($pj->denda_realtime > 0)
                                                        <span class="text-denda" style="white-space: nowrap;">
                                                            Rp {{ number_format($pj->denda_realtime, 0, ',', '.') }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-light text-success"
                                                            style="font-size: 10px; white-space: nowrap;">
                                                            <i class="fas fa-clock mr-1"></i> Rp 0
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>

                                            {{-- GANTI BAGIAN INI DI index.blade.php --}}
                                            <td>
                                                <span class="badge-custom badge-{{ $pj->status }}">
                                                    {{ strtoupper($pj->status) }}
                                                </span>

                                                {{-- Indikator kalau butuh dicek --}}
                                                @if ($pj->bukti_pembayaran && $pj->status == 'approve')
                                                    <br>
                                                    <span class="badge badge-warning mt-1"
                                                        style="font-size: 10px; animation: pulse 1.5s infinite;">
                                                        <i class="fas fa-exclamation-circle"></i> CEK BUKTI BAYAR
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Action Buttons --}}
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center">

                                                    {{-- TOMBOL DETAIL (Semua Role Bisa Lihat) --}}
                                                    {{-- Karena routenya belum ada, gue kasih link kosong '#' dulu --}}
                                                    <a href="{{ route('peminjaman.show', $pj->id) }}"
                                                        class="btn btn-sm btn-info mr-1" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    {{-- CEK ROLE: JIKA BUKAN KEPALA PERPUSTAKAAN, TAMPILKAN TOMBOL AKSI --}}
                                                    @if (Auth::user()->role !== 'kepala_perpustakaan')
                                                        @if ($pj->status == 'pending')
                                                            <form action="{{ route('peminjaman.approve', $pj->id) }}"
                                                                method="POST" class="mr-1">
                                                                @csrf
                                                                <button class="btn btn-sm btn-success" title="Approve">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>

                                                            <form action="{{ route('peminjaman.reject', $pj->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button class="btn btn-sm btn-danger" title="Reject">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </form>
                                                        @elseif($pj->status == 'approve')
                                                            <form action="{{ route('peminjaman.return', $pj->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button class="btn btn-sm btn-warning px-2"
                                                                    title="Confirm Return">
                                                                    <i class="fas fa-undo mr-1"></i> Return
                                                                </button>
                                                            </form>
                                                        @else
                                                            <i class="fas fa-check-circle text-muted ml-1"
                                                                title="Selesai"></i>
                                                        @endif
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4 text-muted">
                                                No borrow records found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination Links --}}
                    <div class="card-footer bg-transparent border-0 px-0 mt-3 d-flex justify-content-end">
                        {{ $peminjamans->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
