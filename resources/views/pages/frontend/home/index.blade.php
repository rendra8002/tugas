@extends('layouts.frontend.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Welcome, {{ Auth::user()->name ?? 'User' }}!</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Book List</div>
                </div>
            </div>

            <div class="section-body">
                @php
                    $defaultImage = 'assets/img/no-cover.png';
                    $sliderBg =
                        'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=1000&auto=format&fit=crop';
                @endphp

                <style>
                    /* --- ULTRA FAST SLIDE SYNC --- */
                    #promoSlider {
                        width: 520px;
                        height: 220px;
                        border-radius: 18px;
                        overflow: hidden;
                        background: #1a1a1d;
                    }

                    #promoSlider .carousel-item {
                        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
                    }

                    .custom-indicators {
                        bottom: 15px;
                        margin-right: 20px;
                        justify-content: flex-end;
                        padding-right: 20px;
                        z-index: 10;
                    }

                    .custom-indicators button {
                        width: 8px !important;
                        height: 8px !important;
                        border-radius: 50% !important;
                        background-color: rgba(255, 255, 255, 0.3) !important;
                        border: none !important;
                        margin: 0 4px !important;
                        transition: all 0.1s linear !important;
                    }

                    .custom-indicators button.active {
                        background-color: #fff !important;
                        width: 22px !important;
                        border-radius: 10px !important;
                    }

                    /* --- Layout Styling --- */
                    .rec-card-container {
                        width: 520px;
                        height: 220px;
                        display: flex;
                        background: #1a1a1d;
                    }

                    .rec-book-cover {
                        width: 160px;
                        height: 100%;
                        background-size: cover;
                        background-position: center;
                        z-index: 5;
                    }

                    .rec-details-side {
                        flex: 1;
                        position: relative;
                        display: flex;
                        align-items: flex-start;
                        padding: 25px;
                        overflow: hidden;
                        flex-direction: column;
                        justify-content: flex-start;
                    }

                    .bg-blur {
                        position: absolute;
                        inset: 0;
                        background-size: cover;
                        background-position: center;
                        filter: blur(25px) brightness(0.4);
                        transform: scale(1.5);
                        z-index: 1;
                    }

                    .overlay-glass {
                        position: absolute;
                        inset: 0;
                        background: rgba(0, 0, 0, 0.25);
                        z-index: 2;
                    }

                    .rec-texts {
                        position: relative;
                        z-index: 3;
                        color: #ffffff;
                    }

                    .rec-texts .title {
                        font-size: 22px;
                        font-weight: 700;
                        margin: 0;
                    }

                    .rec-texts .year {
                        font-size: 14px;
                        color: rgba(255, 255, 255, 0.5);
                        margin-bottom: 12px;
                    }

                    .rec-texts .desc {
                        font-size: 13px;
                        color: rgba(255, 255, 255, 0.7);
                        line-height: 1.6;
                        display: -webkit-box;
                        -webkit-line-clamp: 3;
                        -webkit-box-orient: vertical;
                        overflow: hidden;
                    }

                    .dashboard-col-right {
                        display: flex;
                        flex-direction: column;
                        gap: 14px;
                        width: 520px;
                    }

                    .last-check-item {
                        background: #1e1e1e;
                        border-radius: 14px;
                        display: flex;
                        align-items: center;
                        width: 100%;
                        height: 103px;
                        border: 1px solid rgba(255, 255, 255, 0.03);
                        overflow: hidden;
                        transition: 0.3s;
                    }

                    .last-check-item:hover {
                        background: #252525;
                        transform: translateX(8px);
                    }

                    .last-check-img {
                        width: 75px;
                        height: 100%;
                        object-fit: cover;
                        margin-right: 20px;
                    }

                    .last-check-info h6 {
                        color: #fff;
                        font-size: 16px;
                        margin: 0;
                        font-weight: 600;
                    }

                    .last-check-info p {
                        color: #888;
                        font-size: 13px;
                    }

                    /* --- FULL WIDTH TABS --- */
                    .custom-full-tabs {
                        display: flex;
                        width: 100%;
                        border-bottom: none !important;
                        background-color: #2b2b30;
                        border-radius: 12px 12px 0 0;
                        overflow: hidden;
                        margin: 0;
                        padding: 0;
                    }

                    .custom-full-tabs .nav-item {
                        flex-grow: 1;
                        flex-basis: 0;
                        margin: 0;
                    }

                    .custom-full-tabs .nav-link {
                        width: 100%;
                        text-align: center;
                        border: none !important;
                        border-radius: 0 !important;
                        color: #a3a3a3;
                        font-weight: 700;
                        font-size: 16px;
                        padding: 16px 10px;
                        background: transparent;
                        transition: all 0.3s ease;
                    }

                    .custom-full-tabs .nav-link.active {
                        background-color: #865942 !important;
                        color: #ffffff !important;
                    }

                    .custom-full-tabs .nav-link:hover:not(.active) {
                        background-color: #38383e;
                        color: #ffffff;
                    }

                    .custom-tab-content-area {
                        background-color: #1a1a1d;
                        border-radius: 0 0 12px 12px;
                        padding: 25px;
                    }

                    /* --- CATEGORY FILTER STYLING --- */
                    .filter-wrapper {
                        display: flex;
                        gap: 10px;
                        overflow-x: auto;
                        padding-bottom: 12px;
                        margin-bottom: 20px;
                        scrollbar-width: thin;
                        scrollbar-color: #444 transparent;
                    }

                    .filter-wrapper::-webkit-scrollbar {
                        height: 4px;
                    }

                    .filter-wrapper::-webkit-scrollbar-thumb {
                        background-color: #444;
                        border-radius: 10px;
                    }

                    .btn-category-filter {
                        background-color: #252531;
                        color: #aaa;
                        border: 1px solid #333;
                        padding: 6px 20px;
                        border-radius: 30px;
                        font-size: 13px;
                        font-weight: 600;
                        white-space: nowrap;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    }

                    .btn-category-filter:hover {
                        background-color: #383848;
                        color: #fff;
                    }

                    .btn-category-filter.active {
                        background-color: #865942;
                        color: #fff;
                        border-color: #865942;
                        box-shadow: 0 4px 10px rgba(134, 89, 66, 0.3);
                    }
                </style>

                <div class="container-fluid px-4 mt-4">
                    <div class="row">
                        {{-- SLIDER SECTION --}}
                        <div class="col-lg-6 mb-4">
                            <h6 class="text-white-50 mb-3 text-capitalize" style="font-size: 15px; letter-spacing: 1.2px;">
                                Recommendation</h6>
                            <div id="promoSlider" class="carousel slide" data-ride="carousel">
                                <div class="carousel-indicators custom-indicators">
                                    @foreach ($recommendedBooks as $index => $book)
                                        <button type="button" data-target="#promoSlider"
                                            data-slide-to="{{ $index }}"
                                            class="{{ $index == 0 ? 'active' : '' }}"></button>
                                    @endforeach
                                </div>
                                <div class="carousel-inner">
                                    @foreach ($recommendedBooks as $index => $book)
                                        @php
                                            $imgUrl = asset('assets/img/no-cover.png');
                                            if ($book->image) {
                                                if (
                                                    \Illuminate\Support\Str::startsWith($book->image, [
                                                        'http://',
                                                        'https://',
                                                    ])
                                                ) {
                                                    $imgUrl = $book->image;
                                                } elseif (
                                                    \Illuminate\Support\Str::startsWith($book->image, 'assets/')
                                                ) {
                                                    $imgUrl = asset($book->image);
                                                } else {
                                                    $imgUrl = asset('storage/' . $book->image);
                                                }
                                            }
                                        @endphp
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <a href="{{ route('book.show', $book->id) }}" style="text-decoration: none;">
                                                <div class="rec-card-container">
                                                    <div class="rec-book-cover"
                                                        style="background-image: url('{{ $imgUrl }}');"></div>
                                                    <div class="rec-details-side">
                                                        <div class="bg-blur"
                                                            style="background-image: url('{{ $imgUrl }}');"></div>
                                                        <div class="overlay-glass"></div>
                                                        <div class="rec-texts">
                                                            <h4 class="title">
                                                                {{ \Illuminate\Support\Str::limit($book->title, 25) }}</h4>
                                                            <p class="year">{{ $book->year }} &bull; {{ $book->author }}
                                                            </p>
                                                            <p class="desc">{{ $book->description }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- LAST CHECKED OUT SECTION --}}
                        <div class="col-lg-6 mb-4">
                            <h6 class="text-white-50 mb-3 text-capitalize" style="font-size: 15px; letter-spacing: 1.2px;">
                                Last Checked Out</h6>
                            <div class="dashboard-col-right">
                                @forelse($lastCheckedOut as $pinjam)
                                    @php
                                        $book = $pinjam->book;
                                        $imgUrl = asset('assets/img/no-cover.png');
                                        if ($book && $book->image) {
                                            if (
                                                \Illuminate\Support\Str::startsWith($book->image, [
                                                    'http://',
                                                    'https://',
                                                ])
                                            ) {
                                                $imgUrl = $book->image;
                                            } elseif (\Illuminate\Support\Str::startsWith($book->image, 'assets/')) {
                                                $imgUrl = asset($book->image);
                                            } else {
                                                $imgUrl = asset('storage/' . $book->image);
                                            }
                                        }
                                    @endphp
                                    <a href="{{ $book ? route('book.show', $book->id) : '#' }}"
                                        style="text-decoration: none;">
                                        <div class="last-check-item">
                                            <img src="{{ $imgUrl }}" class="last-check-img"
                                                onerror="this.src='{{ asset('assets/img/no-cover.png') }}'">
                                            <div class="last-check-info">
                                                <h6>{{ $book ? \Illuminate\Support\Str::limit($book->title, 30) : 'Buku Dihapus' }}
                                                </h6>
                                                <p class="mb-0 text-white-50" style="font-size: 12px;">Status: <span
                                                        class="text-white">{{ ucfirst($pinjam->status) }}</span></p>
                                                <small style="color: #666; font-size: 11px;">Aktivitas:
                                                    {{ \Carbon\Carbon::parse($pinjam->updated_at)->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="last-check-item" style="justify-content: center;">
                                        <p class="text-white-50 mb-0">Kamu belum memiliki riwayat peminjaman.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4" style="background: transparent; border-radius: 12px;">
                    <div class="card-body p-0">
                        <ul class="nav nav-tabs custom-full-tabs" id="bookTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="popular-tab" data-toggle="tab" href="#popular" role="tab"
                                    aria-controls="popular" aria-selected="true">Popular</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="all-tab" data-toggle="tab" href="#all-books" role="tab"
                                    aria-controls="all-books" aria-selected="false">All Books</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="borrowed-tab" data-toggle="tab" href="#borrowed" role="tab"
                                    aria-controls="borrowed" aria-selected="false">My Borrowed</a>
                            </li>
                        </ul>

                        <div class="tab-content custom-tab-content-area pt-3 pb-4 px-4" id="bookTabContent">

                            {{-- 1. TAB POPULAR --}}
                            <div class="tab-pane fade show active" id="popular" role="tabpanel"
                                aria-labelledby="popular-tab">
                                <div
                                    class="bg-transparent border-0 px-0 mb-3 d-flex justify-content-between align-items-center flex-wrap">
                                    <h4 class="text-white mb-2">Popular Books</i>
                                    </h4>
                                </div>
                                <div class="filter-wrapper">
                                    <button class="btn-category-filter active" data-filter="all">All Categories</button>
                                    @foreach ($categories as $category)
                                        <button class="btn-category-filter"
                                            data-filter="{{ $category->id }}">{{ $category->name }}</button>
                                    @endforeach
                                </div>
                                <div class="row five-cols">
                                    @forelse($popularBooks ?? [] as $book)
                                        <div class="col-item book-item" data-category="{{ $book->category_id }}">
                                            <x-book-card :book="$book" :link="route('book.show', $book->id)" />
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-5 empty-state">
                                            <p class="text-white-50">Belum ada data buku populer saat ini.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- 2. TAB ALL BOOKS --}}
                            <div class="tab-pane fade" id="all-books" role="tabpanel" aria-labelledby="all-tab">
                                <div class="bg-transparent border-0 px-0 mb-3">
                                    <h4 class="text-white mb-2">Book List</h4>
                                </div>
                                <div class="filter-wrapper">
                                    <button class="btn-category-filter active" data-filter="all">All Categories</button>
                                    @foreach ($categories as $category)
                                        <button class="btn-category-filter"
                                            data-filter="{{ $category->id }}">{{ $category->name }}</button>
                                    @endforeach
                                </div>
                                <div class="row five-cols">
                                    @forelse($books ?? [] as $book)
                                        <div class="col-item book-item" data-category="{{ $book->category_id }}">
                                            <x-book-card :book="$book" :link="route('book.show', $book->id)" />
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-5 empty-state">
                                            <p class="text-white-50">Data tidak ditemukan.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="tab-pane fade" id="borrowed" role="tabpanel" aria-labelledby="borrowed-tab">
                                <div class="bg-transparent border-0 px-0 mb-3">
                                    <h4 class="text-white mb-2">Books I Borrowed</h4>
                                </div>

                                <div class="filter-wrapper">
                                    <button class="btn-category-filter active" data-filter="all">All Categories</button>
                                    @foreach ($borrowedCategories as $category)
                                        <button class="btn-category-filter"
                                            data-filter="{{ $category->id }}">{{ $category->name }}</button>
                                    @endforeach
                                </div>

                                {{-- KITA UBAH LOGIKANYA JADI @if @else --}}
                                @if (count($myBorrowedBooks ?? []) > 0)
                                    <div class="row five-cols">
                                        @foreach ($myBorrowedBooks as $book)
                                            <div class="col-item book-item" data-category="{{ $book->category_id }}">
                                                <x-book-card :book="$book" :link="route('book.show', $book->id)" />
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    {{-- EMPTY STATE SEKARANG BEBAS DARI JERATAN .five-cols --}}
                                    <div class="d-flex justify-content-center align-items-center py-5 empty-state"
                                        style="min-height: 250px; width: 100%;">
                                        <div class="text-center">
                                            <i class="fas fa-book-reader text-white-50 mb-3"
                                                style="font-size: 40px; opacity: 0.5;"></i>
                                            <p class="text-white-50 mb-0" style="font-size: 15px; letter-spacing: 0.5px;">
                                                Kamu sedang tidak meminjam buku apa pun saat ini.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- SCRIPT JAVASCRIPT FINAL --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof $ !== 'undefined') {

                // 1. LOGIKA SLIDER
                var $slider = $('#promoSlider');
                if ($slider.length) {
                    $slider.carousel({
                        interval: 4000,
                        pause: false
                    });

                    $slider.on('slide.bs.carousel', function(e) {
                        var index = e.to;
                        $('.custom-indicators button').removeClass('active').eq(index).addClass('active');
                    });
                }

                // 2. LOGIKA FILTER KATEGORI 
                $('.btn-category-filter').off('click').on('click', function(e) {
                    e.preventDefault();

                    var $this = $(this);
                    var $currentTabPane = $this.closest('.tab-pane');
                    var filterValue = $this.attr('data-filter');

                    $currentTabPane.find('.btn-category-filter').removeClass('active');
                    $this.addClass('active');

                    var $booksInTab = $currentTabPane.find('.book-item');

                    if (filterValue === 'all') {
                        $booksInTab.stop(true, true).fadeIn(300);
                    } else {
                        $booksInTab.hide();
                        $currentTabPane.find('.book-item[data-category="' + filterValue + '"]').stop(true,
                            true).fadeIn(300);
                    }
                });

                // 3. RESET FILTER SAAT PINDAH TAB
                $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                    var targetPaneId = $(e.target).attr("href");
                    $(targetPaneId).find('.btn-category-filter[data-filter="all"]').trigger('click');
                });

            } else {
                console.error("jQuery tidak terdeteksi. Pastikan file script jQuery dimuat sebelum kode ini.");
            }
        });
    </script>
@endsection
