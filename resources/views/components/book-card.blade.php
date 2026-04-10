@props([
    'book' => null,
    'link' => '#',
    'title' => 'Untitled',
    'status' => 'Available',
])

@php
    $displayImage = asset('assets/img/no-cover.png'); // Default awal

    if ($book && $book->image) {
        if (Str::startsWith($book->image, ['http://', 'https://'])) {
            $displayImage = $book->image;
        } elseif (Str::startsWith($book->image, 'assets/')) {
            $displayImage = asset($book->image);
        } else {
            // PERBAIKAN: Hapus kata 'books/' karena sudah bawaan dari database
            $displayImage = asset('storage/' . $book->image);
        }
    }

    $displayTitle = $book->title ?? ($title ?? 'Untitled Book');
    $displayStatus = $book->status ?? ($status ?? 'Available');
@endphp

<a href="{{ $link }}" class="book-card-link" style="text-decoration: none; display: block;">
    <article class="book-card" style="background-image: url('{{ $displayImage }}');">
        <div class="article-header">
            <button class="btn btn-available"
                style="background-color: {{ strtolower($displayStatus) == 'avaiable' ? '#6777ef' : '#fc544b' }}">
                {{ str_replace('not ', '', $displayStatus) }}
            </button>
            <div class="article-title">
                <h2>{{ $displayTitle }}</h2>
            </div>
        </div>
    </article>
</a>

@push('css.buku')
    <style>
        /* batas */
        .five-cols {
            display: flex;
            flex-wrap: wrap;
        }

        .five-cols .col-item {
            /* 100% dibagi 5 = 20% */
            flex: 0 0 20%;
            max-width: 20%;
            padding: 0 10px;
            /* Jarak antar kartu */
            display: flex;
            justify-content: center;
        }

        /* Responsif: Di tablet jadi 3 kartu, di HP jadi 2 kartu */
        @media (max-width: 992px) {
            .five-cols .col-item {
                flex: 0 0 33.33%;
                max-width: 33.33%;
            }
        }

        @media (max-width: 576px) {
            .five-cols .col-item {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        /* batas */

        .book-card {
            width: 195px;
            height: 290px;
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            margin-bottom: 20px;
            display: block;
        }

        /* Tambahkan efek hover pada pembungkusnya juga biar smooth */
        .book-card-link:hover {
            text-decoration: none;
        }

        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px -5px rgba(255, 255, 255, 0.25);
        }

        .book-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60%;
            z-index: 1;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent);
        }

        .book-card .article-header {
            position: relative;
            height: 100%;
            z-index: 2;
        }

        .book-card .btn-available {
            position: absolute;
            top: 0;
            right: 0;
            color: white !important;
            border: none;
            border-radius: 0 10px 0 10px;
            padding: 5px 15px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .book-card .article-title {
            position: absolute;
            bottom: 15px;
            left: 15px;
            right: 15px;
            margin: 0;
        }

        .book-card .article-title h2 {
            color: white !important;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.2;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }
    </style>
@endpush
