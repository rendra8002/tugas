@props([
    'image' => asset('assets/r/557779963_3486861214786610_5644905288489553813_n (1).jpg'),
    'status' => 'Available',
    'title' => 'Untitled Book',
])

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

        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.25);
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
        }
    </style>
@endpush

<article class="book-card" style="background-image: url('{{ $image }}');">
    <div class="article-header">
        {{-- Warna dinamis berdasarkan status --}}
        <button class="btn btn-available"
            style="background-color: {{ strtolower($status) == 'avaiable' ? '#6777ef' : '#fc544b' }}">
            {{ $status }}
        </button>
        <div class="article-title">
            <h2>{{ $title }}</h2>
        </div>
    </div>
</article>
