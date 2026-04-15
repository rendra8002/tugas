@props([
    'book' => null,
    'link' => '#',
    'title' => 'Untitled',
    'status' => 'Available',
])

@php
    $displayImage = asset('assets/img/no-cover.png');
    if ($book) {
        $imgField = $book->image;
        if ($imgField) {
            if (\Illuminate\Support\Str::startsWith($imgField, ['http://', 'https://'])) {
                $displayImage = $imgField;
            } elseif (\Illuminate\Support\Str::startsWith($imgField, 'assets/')) {
                $displayImage = asset($imgField);
            } else {
                $displayImage = asset('storage/' . $imgField);
            }
        }
    }

    $displayTitle = $book->title ?? $title;
    // Cek status untuk menentukan warna tombol
    $rawStatus = strtolower($book->status ?? $status);
    $isAvailable = ($rawStatus == 'avaiable' || $rawStatus == 'available');
@endphp

<a href="{{ $link }}" class="book-card-link" style="text-decoration: none; display: block;">
    <article class="book-card" style="background-image: url('{{ $displayImage }}');">
        <div class="article-header">
            {{-- Tombol Status yang Diperbaiki --}}
            <div class="status-badge {{ $isAvailable ? 'bg-available' : 'bg-out-stock' }}">
                {{ $isAvailable ? 'Available' : 'Out Stock' }}
            </div>
            
            <div class="article-title">
                <h2>{{ \Illuminate\Support\Str::limit($displayTitle, 30) }}</h2>
            </div>
        </div>
    </article>
</a>

@push('css.buku')
    <style>
        /* 1. Perbaikan Layout Grid (6 Kolom) */
        .five-cols {
            display: grid !important;
            grid-template-columns: repeat(6, 1fr) !important; 
            gap: 15px !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Matikan fungsi col-item bawaan agar tidak merusak Grid */
        .five-cols .col-item {
            flex: none !important;
            max-width: 100% !important;
            padding: 0 !important;
        }

        /* 2. Kartu Buku - Rasio 2:3 */
        .book-card {
            width: 100% !important;
            aspect-ratio: 2 / 3 !important;
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #25252b;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
            display: block;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px -5px rgba(255, 255, 255, 0.15);
        }

        /* 3. Style Tombol Status (Badge) */
        .status-badge {
            position: absolute;
            top: 0;
            right: 0;
            padding: 4px 8px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            color: #fff !important;
            border-radius: 0 8px 0 8px;
            z-index: 10;
            letter-spacing: 0.5px;
        }

        .bg-available { background-color: #6777ef !important; } /* Biru Stisla */
        .bg-out-stock { background-color: #fc544b !important; } /* Merah Stisla */

        /* 4. Overlay & Judul */
        .book-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,1) 0%, rgba(0,0,0,0.8) 20%, transparent 60%);
            z-index: 1;
        }

        .book-card .article-title {
            position: absolute;
            bottom: 10px;
            left: 8px;
            right: 8px;
            z-index: 5;
            margin: 0;
            text-align: center;
        }

        .book-card .article-title h2 {
            color: #ffffff !important;
            font-size: 11px !important;
            font-weight: 700;
            line-height: 1.2;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Responsif Grid */
        @media (max-width: 1200px) { .five-cols { grid-template-columns: repeat(5, 1fr) !important; } }
        @media (max-width: 992px) { .five-cols { grid-template-columns: repeat(4, 1fr) !important; } }
        @media (max-width: 768px) { .five-cols { grid-template-columns: repeat(3, 1fr) !important; } }
        @media (max-width: 480px) { .five-cols { grid-template-columns: repeat(2, 1fr) !important; gap: 10px !important; } }
    </style>
@endpush