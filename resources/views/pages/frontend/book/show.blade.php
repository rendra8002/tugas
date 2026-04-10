@extends('layouts.frontend.app')

@section('content')
    <div class="main-content">
        <section class="section">

            <div class="section-header">
                <div class="section-header-back"></div>
                <h1>Detail Buku</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item active">Detail Buku</div>
                </div>
            </div>

            <div class="section-body">
                <style>
                    /* Styling rincian di dalam modal frontend */
                    .info-box-front {
                        background-color: #252531;
                        border: 1px solid #444;
                        border-radius: 8px;
                        padding: 15px;
                        margin-bottom: 15px;
                    }

                    .info-label-front {
                        color: #aaa;
                        font-size: 11px;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                        font-weight: bold;
                        margin-bottom: 5px;
                    }

                    .info-value-front {
                        color: #fff;
                        font-size: 15px;
                        font-weight: 500;
                    }

                    .border-danger {
                        border: 1px solid #fc544b !important;
                    }

                    /* Style lama utuh */
                    .dark-container {
                        background-color: #1a1a24;
                        color: #ffffff;
                        border-radius: 10px;
                        padding: 40px;
                        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                    }

                    .text-label {
                        font-size: 13px;
                        color: #a0a0a0;
                        margin-bottom: 2px;
                    }

                    .text-value {
                        font-size: 15px;
                        font-weight: 500;
                    }

                    .badge-year {
                        background-color: #3b82f6;
                        color: white;
                        padding: 5px 12px;
                        border-radius: 12px;
                        font-weight: normal;
                    }

                    .badge-status {
                        background-color: #6366f1;
                        color: white;
                        padding: 5px 15px;
                        border-radius: 12px;
                        font-weight: normal;
                    }

                    /* Buttons */
                    .btn-checkout {
                        background-color: #8b6b52;
                        color: white;
                        border: none;
                        padding: 8px 25px;
                        border-radius: 5px;
                        transition: 0.3s;
                    }

                    .btn-checkout:hover {
                        background-color: #725641;
                        color: white;
                    }

                    .btn-return {
                        background-color: #2a2a35;
                        color: white;
                        border: none;
                        padding: 8px 25px;
                        border-radius: 5px;
                        transition: 0.3s;
                    }

                    .btn-return:hover {
                        background-color: #1f1f28;
                        color: white;
                    }

                    .btn-danger-custom {
                        background-color: #fc544b;
                        color: white;
                        border: none;
                        padding: 8px 25px;
                        border-radius: 5px;
                        transition: 0.3s;
                    }

                    .btn-danger-custom:hover {
                        background-color: #db473f;
                        color: white;
                    }

                    .book-cover {
                        border-radius: 8px;
                        width: 100%;
                        max-width: 300px;
                        object-fit: cover;
                        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
                    }

                    .divider {
                        border-top: 1px solid #333344;
                        margin: 30px 0;
                    }

                    /* Dark Modal Style */
                    .modal-dark .modal-content {
                        background-color: #1a1a24;
                        color: white;
                        border: 1px solid #444;
                        border-radius: 10px;
                    }

                    .modal-dark .modal-header {
                        border-bottom: 1px solid #333;
                    }

                    .modal-dark .modal-footer {
                        border-top: 1px solid #333;
                    }

                    .modal-dark .close {
                        color: white;
                        text-shadow: none;
                    }

                    .book-cover {
                        border-radius: 8px;
                        width: 100%;
                        max-width: 260px;
                        /* <--- UBAH DI SINI: Nilai diperkecil dari 300px */
                        aspect-ratio: 2 / 3;
                        object-fit: cover;
                        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
                        background-color: #2a2a35;
                        margin: 0 auto;
                        /* Tambahkan ini agar tetap ke tengah jika layar kecil */
                        display: block;
                        /* Tambahkan ini untuk mendukung margin auto */
                    }
                </style>

                <div class="dark-container">
                    <div class="row">

                        <div class="col-md-4 text-center text-md-left mb-4 mb-md-0">
                            @php
                                // Logika pintar untuk mendeteksi sumber gambar
                                $displayImage = asset('assets/img/no-cover.png');

                                if ($book && $book->image) {
                                    if (Str::startsWith($book->image, ['http://', 'https://'])) {
                                        // Dari link luar (Unsplash dll)
                                        $displayImage = $book->image;
                                    } elseif (Str::startsWith($book->image, 'assets/')) {
                                        // Dari seeder lokal (assets/bg/...)
                                        $displayImage = asset($book->image);
                                    } elseif (Str::contains($book->image, 'books/')) {
                                        // Jika sudah ada path foldernya di database
                                        $displayImage = asset('storage/' . $book->image);
                                    } else {
                                        // File murni dari upload (nama file saja)
                                        $displayImage = asset('storage/books/' . $book->image);
                                    }
                                }
                            @endphp

                            {{-- Cover Buku --}}
                            <img src="{{ $displayImage }}" alt="{{ $book->title }}" class="book-cover"
                                onerror="this.src='{{ asset('assets/img/no-cover.png') }}'">

                            <div class="d-flex mt-4 justify-content-center">
                                {{-- LOGIKA TOMBOL MUTUALLY EXCLUSIVE --}}
                                @if (!$activePeminjaman)
                                    {{-- KONDISI 1: Belum pinjam sama sekali -> Tampil tombol PINJAM --}}
                                    <form action="{{ route('book.borrow', $book->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-checkout"
                                            @if ($book->stock <= 0) disabled @endif>
                                            <i class="fas fa-book-reader mr-1"></i> Pinjam Buku Ini
                                        </button>
                                    </form>
                                @elseif ($activePeminjaman->status == 'pending')
                                    {{-- KONDISI 2: Sedang Pending -> Tombol Pinjam hilang --}}
                                    <button class="btn btn-secondary" disabled
                                        style="background-color: #333344; border: none; color: #a0a0a0; padding: 8px 25px; border-radius: 5px;">
                                        <i class="fas fa-hourglass-half mr-1"></i> Menunggu Persetujuan...
                                    </button>
                                @elseif ($activePeminjaman->status == 'verifikasi')
                                    {{-- KONDISI 3: Sedang Verifikasi Bukti Bayar Denda --}}
                                    <button class="btn btn-info" disabled
                                        style="background-color: #17a2b8; color: white; border: none; padding: 8px 25px; border-radius: 5px; cursor: not-allowed;">
                                        <i class="fas fa-search-dollar mr-1"></i> Sedang Verifikasi...
                                    </button>
                                @elseif ($activePeminjaman->status == 'approve')
                                    {{-- KONDISI 4: Sudah Approve -> Tampil tombol KEMBALIKAN (Buka Modal) --}}
                                    <button type="button" class="btn {{ $isOverdue ? 'btn-danger-custom' : 'btn-info' }}"
                                        data-toggle="modal" data-target="#returnModal"
                                        style="{{ !$isOverdue ? 'background-color: #17a2b8; border: none; color: white; padding: 8px 25px; border-radius: 5px; transition: 0.3s;' : '' }}">
                                        @if ($isOverdue)
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Kembalikan (Terlambat)
                                        @else
                                            <i class="fas fa-undo mr-1"></i> Kembalikan Buku
                                        @endif
                                    </button>
                                @endif
                            </div>

                            {{-- KETERANGAN REMAINING DAYS / DENDA --}}
                            <div class="mt-3 text-center text-md-left">
                                @if ($isApproved)
                                    @if ($isOverdue)
                                        <span style="color: #fc544b; font-size: 14px; font-weight: 600;">
                                            <i class="fas fa-times-circle mr-1"></i> Terlambat {{ $remainingDays }} Hari
                                        </span>
                                    @else
                                        <span
                                            style="color: #28a745; font-size: 14px; letter-spacing: 1px; font-weight: 500;">
                                            <i class="fas fa-calendar-check mr-1"></i> Sisa Waktu: {{ $remainingDays }} Hari
                                        </span>
                                    @endif
                                @elseif ($isPending)
                                    <span style="color: #a0a0a0; font-size: 13px; letter-spacing: 1px;">
                                        Permintaan sedang diproses petugas.
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Bagian Kanan (Info Buku) --}}
                        <div class="col-md-8 pl-md-5">
                            <h2 class="font-weight-bold mb-1 text-white" style="font-size: 2.5rem;">{{ $book->title }}</h2>
                            <span class="badge badge-year mb-4">{{ $book->year }}</span>

                            <div class="row pb-3 mb-3" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <div class="col-6">
                                    <div class="text-label">Author</div>
                                    <div class="text-value mb-0">{{ $book->author }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-label">Status Buku Sistem</div>
                                    @if (strtolower($book->status) == 'avaiable' || $book->stock > 0)
                                        <span class="badge badge-status mt-1"
                                            style="background-color: #6366f1;">Available</span>
                                    @else
                                        <span class="badge badge-status mt-1" style="background-color: #fc544b;">Out of
                                            Stock</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row pb-3 mb-3" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <div class="col-12">
                                    <div class="text-label">Stock Tersedia</div>
                                    <div class="text-value mb-0">{{ $book->stock }} Pcs</div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="text-label">Description</div>
                                    <div class="text-value"
                                        style="font-size: 14px; font-weight: normal; color: #d0d0d0; line-height: 1.6;">
                                        {{ $book->description }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    <div class="text-center d-flex justify-content-center align-items-center flex-wrap" style="gap: 10px;">
                        <button class="btn btn-sm"
                            style="background-color: #4a3525; color: white; border-radius: 5px; padding: 8px 20px;">
                            <i class="far fa-bookmark mr-1"></i> Bookmark
                        </button>

                        {{-- TOMBOL CETAK UNTUK TRANSAKSI AKTIF --}}
                        {{-- TOMBOL CETAK UNTUK TRANSAKSI AKTIF --}}
                        @if ($activePeminjaman)
                            <a href="{{ route('peminjaman.print', $activePeminjaman->id) }}" class="btn btn-sm"
                                style="background-color: #4f46e5; color: white; border-radius: 5px; padding: 8px 20px; text-decoration: none;">
                                <i class="fas fa-file-download mr-1"></i> Download Bukti Pinjam (PDF)
                            </a>
                        @endif

                        @if ($lastReturned)
                            <div class="text-center mt-3">
                                <a href="{{ route('peminjaman.printed', $lastReturned->id) }}"
                                    onclick="setTimeout(() => { location.reload(); }, 1500);" class="btn btn-sm"
                                    style="background-color: #28a745; color: white; border-radius: 5px; padding: 8px 20px; text-decoration: none;">
                                    <i class="fas fa-file-pdf mr-1"></i> Download Struk Lunas (PDF)
                                </a>
                                <div class="text-muted mt-1" style="font-size: 11px;">
                                    *File hanya bisa didownload 1x setelah lunas
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </section>
    </div>

    {{-- MODAL PENGEMBALIAN BUKU UNIVERSAL --}}
    @if ($isApproved)
        <div class="modal fade modal-dark" id="returnModal" tabindex="-1" role="dialog" aria-labelledby="returnModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0 pb-0 pt-4">
                        <h5 class="modal-title" id="returnModalLabel" style="color: white;">
                            <i class="fas fa-file-invoice mr-2"></i> Rincian Pengembalian Buku
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('book.return', $book->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body pt-4 pb-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box-front">
                                        <div class="info-label-front">Tanggal Pinjam</div>
                                        <div class="info-value-front mb-3">
                                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y') }}
                                        </div>

                                        <div class="info-label-front">Batas Waktu (Jatuh Tempo)</div>
                                        <div class="info-value-front">
                                            {{ \Carbon\Carbon::parse($peminjaman->jatuh_tempo)->format('d F Y') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box-front">
                                        <div class="info-label-front">Status Pengembalian</div>
                                        <div class="info-value-front mb-3">
                                            @if ($isOverdue)
                                                <span style="color: #fc544b; font-weight: bold;">
                                                    <i class="fas fa-times-circle mr-1"></i> Terlambat
                                                    {{ $remainingDays }} Hari
                                                </span>
                                            @else
                                                <span style="color: #28a745; font-weight: bold;">
                                                    <i class="fas fa-check-circle mr-1"></i> Tepat Waktu (Sisa
                                                    {{ $remainingDays }} Hari)
                                                </span>
                                            @endif
                                        </div>

                                        <div class="info-label-front">Total Denda</div>
                                        <div class="info-value-front">
                                            @if ($isOverdue)
                                                <span style="color: #fc544b; font-size: 1.2rem; font-weight: bold;">
                                                    Rp {{ number_format($denda, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span style="color: #a0a0a0;">Tidak ada denda (Rp 0)</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($isOverdue)
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="info-box-front border-danger"
                                            style="background-color: rgba(252, 84, 75, 0.05);">
                                            <label class="text-danger small font-weight-bold"
                                                style="letter-spacing: 1px;">
                                                <i class="fas fa-upload mr-1"></i> UPLOAD BUKTI PEMBAYARAN DENDA
                                            </label>
                                            <div class="custom-file mt-1">
                                                <input type="file" name="bukti_pembayaran" class="custom-file-input"
                                                    id="buktiBayar" required>
                                                <label class="custom-file-label"
                                                    style="background-color: #1a1a24; color: #aaa; border: 1px solid #fc544b;"
                                                    for="buktiBayar">
                                                    Pilih file gambar struk/transfer...
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="modal-footer border-top-0 pt-0 pb-4 pr-4">
                            <button type="button" class="btn btn-secondary px-4" data-dismiss="modal"
                                style="background-color: #333344; border: none;">Batal</button>

                            @if ($isOverdue)
                                <button type="submit" class="btn btn-danger-custom px-4">
                                    <i class="fas fa-file-invoice-dollar mr-1"></i> Bayar Denda & Kembalikan
                                </button>
                            @else
                                <button type="submit" class="btn btn-info px-4"
                                    style="background-color: #17a2b8; border: none;">
                                    <i class="fas fa-undo mr-1"></i> Konfirmasi Pengembalian
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Script agar nama file muncul saat diupload --}}
        @push('css.buku')
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    $('#buktiBayar').on('change', function() {
                        var fileName = $(this).val().split('\\').pop();
                        $(this).next('.custom-file-label').html(fileName);
                    });
                });
            </script>
        @endpush
    @endif
@endsection
