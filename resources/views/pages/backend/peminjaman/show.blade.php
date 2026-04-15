@extends('layouts.backend.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Detail Peminjaman</h1>
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

                    .badge-custom {
                        padding: 6px 15px;
                        border-radius: 4px;
                        font-weight: 600;
                        font-size: 12px;
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
                        padding: 4px 10px;
                        border-radius: 4px;
                    }

                    .info-box {
                        background-color: #252531;
                        border: 1px solid #444;
                        border-radius: 8px;
                        padding: 20px;
                        height: 100%;
                    }

                    .info-label {
                        color: #aaa;
                        font-size: 11px;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                        font-weight: bold;
                        margin-bottom: 5px;
                    }

                    .info-value {
                        color: #fff;
                        font-size: 16px;
                        font-weight: 600;
                        margin-bottom: 20px;
                    }
                </style>

                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm" style="background-color: #1a1a24; border-radius: 12px;">
                            <div class="card-header border-bottom-0 pb-0 pt-4">
                                <h4 style="color: white;"><i class="fas fa-file-invoice mr-2"></i>Rincian Transaksi
                                    Peminjaman</h4>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-4 mb-md-0 pr-md-4">
                                        <div class="d-flex align-items-center mb-4 pb-4"
                                            style="border-bottom: 1px dashed #444;">
                                            @php
                                                $user = $peminjaman->user;
                                                $dbValue = $user->getRawOriginal('image');
                                                $finalUrl = '';

                                                if ($dbValue) {
                                                    if (Str::startsWith($dbValue, ['http://', 'https://'])) {
                                                        $finalUrl = $dbValue;
                                                    } elseif (Str::startsWith($dbValue, 'assets/')) {
                                                        $finalUrl = asset($dbValue);
                                                    } else {
                                                        $finalUrl = asset('storage/users/' . $dbValue);
                                                    }
                                                } else {
                                                    $finalUrl =
                                                        'https://ui-avatars.com/api/?name=' .
                                                        urlencode($user->name) .
                                                        '&background=random&color=fff';
                                                }
                                            @endphp

                                            <img src="{{ $finalUrl }}" alt="avatar"
                                                style="width: 75px; height: 75px; border-radius: 50%; object-fit: cover; border: 3px solid #333;"
                                                class="mr-4"
                                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff'">

                                            <div>
                                                <div class="info-label">Peminjam</div>
                                                <div class="info-value mb-1" style="font-size: 18px;">
                                                    {{ $peminjaman->user->name }}</div>
                                                <div class="text-muted small">
                                                    <i class="fas fa-envelope mr-1"></i>{{ $peminjaman->user->email }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center pb-3">
                                            <img src="{{ $peminjaman->book->image ? (str_starts_with($peminjaman->book->image, 'assets') ? asset($peminjaman->book->image) : asset('storage/' . $peminjaman->book->image)) : 'https://via.placeholder.com/150x200?text=No+Cover' }}"
                                                alt="cover"
                                                style="width: 70px; height: 105px; border-radius: 6px; object-fit: cover; box-shadow: 0 4px 8px rgba(0,0,0,0.3);"
                                                class="mr-4">
                                            <div>
                                                <div class="info-label">Buku yang Dipinjam</div>
                                                <div class="info-value mb-1">{{ $peminjaman->book->title }}</div>
                                                <div class="text-muted small"><i class="fas fa-pen-nib mr-1"></i>Author:
                                                    {{ $peminjaman->book->author }}</div>
                                                <div class="text-muted small mt-1"><i
                                                        class="fas fa-layer-group mr-1"></i>Sisa Stok Sistem:
                                                    {{ $peminjaman->book->stock }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="info-label">Status Peminjaman</div>
                                                    <div class="mb-4">
                                                        <span class="badge-custom badge-{{ $peminjaman->status }}">
                                                            {{ strtoupper($peminjaman->status) }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="info-label">Denda Keterlambatan</div>
                                                    <div class="mb-4">
                                                        @php
                                                            // FIX: Pakai abs() biar angka negatif di DB tetap kebaca dan tampil positif
                                                            $nominalDendaAbs = abs((int) $peminjaman->denda_realtime);
                                                            $dendaDBAbs = abs((int) $peminjaman->total_denda);

                                                            $hariIni = \Carbon\Carbon::now()->startOfDay();
                                                            $jt = \Carbon\Carbon::parse(
                                                                $peminjaman->jatuh_tempo,
                                                            )->startOfDay();
                                                        @endphp

                                                        @if ($peminjaman->status == 'returned')
                                                            @if ($dendaDBAbs > 0)
                                                                <span class="badge badge-success"
                                                                    style="font-size: 12px; background-color: #28a745; color: white;">
                                                                    <i class="fas fa-check-double mr-1"></i> LUNAS (Rp
                                                                    {{ number_format($dendaDBAbs, 0, ',', '.') }})
                                                                </span>
                                                            @else
                                                                <span class="badge badge-info"
                                                                    style="font-size: 12px; background-color: #17a2b8; color: white;">
                                                                    <i class="fas fa-check-circle mr-1"></i> Tepat Waktu (Rp
                                                                    0)
                                                                </span>
                                                            @endif
                                                        @elseif (in_array($peminjaman->status, ['pending', 'rejected']))
                                                            <span class="text-muted">-</span>
                                                        @else
                                                            @if ($nominalDendaAbs > 0)
                                                                <span class="text-denda" style="font-size: 14px;">
                                                                    <i class="fas fa-exclamation-triangle mr-1"></i> Rp
                                                                    {{ number_format($nominalDendaAbs, 0, ',', '.') }}
                                                                </span>
                                                            @elseif ($hariIni->equalTo($jt))
                                                                <span class="badge badge-warning text-dark"
                                                                    style="font-size: 12px; font-weight: bold;">
                                                                    <i class="fas fa-hourglass-half mr-1"></i> Terakhir Hari
                                                                    Ini
                                                                </span>
                                                            @else
                                                                <span style="color: #28a745; font-weight: 600;">
                                                                    <i class="fas fa-clock mr-1"></i> Rp 0 (Aman)
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <hr style="border-color: #444; margin-top: 0;">
                                                </div>

                                                <div class="col-6 mt-2">
                                                    <div class="info-label">Tanggal Pinjam</div>
                                                    <div class="info-value" style="font-size: 14px;">
                                                        @if ($peminjaman->status == 'pending')
                                                            <span class="text-muted">Menunggu Antrean</span>
                                                        @elseif ($peminjaman->status == 'rejected')
                                                            <span>-</span>
                                                        @else
                                                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y') }}
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-6 mt-2">
                                                    <div class="info-label">Jatuh Tempo</div>
                                                    <div class="info-value" style="font-size: 14px;">
                                                        @if (in_array($peminjaman->status, ['pending', 'rejected']))
                                                            <span class="text-muted">-</span>
                                                        @else
                                                            <span
                                                                class="{{ $peminjaman->status == 'approve' && \Carbon\Carbon::now()->gt($peminjaman->jatuh_tempo) ? 'text-warning' : '' }}">
                                                                {{ \Carbon\Carbon::parse($peminjaman->jatuh_tempo)->format('d F Y') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12 mt-3">
                                                    <div class="info-label">Dikembalikan Pada</div>
                                                    <div class="info-value mb-0" style="font-size: 14px;">
                                                        @if ($peminjaman->tanggal_kembali)
                                                            <span class="text-success"><i
                                                                    class="fas fa-check-circle mr-1"></i>
                                                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d F Y, H:i') }}</span>
                                                        @elseif ($peminjaman->status == 'rejected')
                                                            <span class="text-muted">-</span>
                                                        @else
                                                            <span class="text-muted"><i class="fas fa-minus mr-1"></i> Belum
                                                                Dikembalikan</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if ($peminjaman->bukti_pembayaran)
                                                    <div class="col-12 mt-4 pt-3" style="border-top: 1px dashed #444;">
                                                        <div class="info-label text-warning" style="font-size: 13px;">
                                                            <i class="fas fa-file-invoice-dollar mr-1"></i> Bukti Pembayaran
                                                            Denda
                                                        </div>
                                                        <div class="mt-2 text-center text-md-left">
                                                            <a href="{{ asset('storage/' . $peminjaman->bukti_pembayaran) }}"
                                                                target="_blank">
                                                                <img src="{{ asset('storage/' . $peminjaman->bukti_pembayaran) }}"
                                                                    alt="Bukti Transfer" class="img-fluid rounded"
                                                                    style="max-height: 200px; border: 2px solid #5a67d8; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if (Auth::user()->role !== 'kepala_perpustakaan')
                                <div class="card-footer border-0 d-flex justify-content-end pt-3 pb-4 pr-4"
                                    style="background-color: transparent;">
                                    @if ($peminjaman->status == 'pending')
                                        <form action="{{ route('peminjaman.reject', $peminjaman->id) }}" method="POST"
                                            class="mr-2">
                                            @csrf
                                            <button class="btn btn-danger font-weight-bold px-4"><i
                                                    class="fas fa-times mr-1"></i> Tolak</button>
                                        </form>
                                        <form action="{{ route('peminjaman.approve', $peminjaman->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-success font-weight-bold px-4"><i
                                                    class="fas fa-check mr-1"></i> Setujui</button>
                                        </form>
                                    @elseif($peminjaman->status == 'verifikasi')
                                        <div class="d-flex">
                                            <form action="{{ route('peminjaman.return', $peminjaman->id) }}"
                                                method="POST" class="mr-2">
                                                @csrf
                                                <input type="hidden" name="action" value="reject_payment">
                                                <button type="submit"
                                                    class="btn btn-outline-danger font-weight-bold px-4">
                                                    <i class="fas fa-times-circle mr-1"></i> Tolak Bukti Bayar
                                                </button>
                                            </form>
                                            <form action="{{ route('peminjaman.return', $peminjaman->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="approve_payment">
                                                <button type="submit" class="btn btn-success font-weight-bold px-4">
                                                    <i class="fas fa-check-double mr-1"></i> Bukti Valid & Selesaikan
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
