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
                    // Pastikan default image ini mengarah ke file yang benar ada di public/assets/img
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
                        align-items: center;
                        padding: 25px;
                        overflow: hidden;
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
                </style>

                <div class="container-fluid px-4 mt-4">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <h6 class="text-white-50 mb-3 text-capitalize" style="font-size: 15px; letter-spacing: 1.2px;">
                                Recommendation</h6>
                            <div id="promoSlider" class="carousel slide" data-ride="carousel">
                                <div class="carousel-indicators custom-indicators">
                                    <button type="button" data-target="#promoSlider" data-slide-to="0"
                                        class="active"></button>
                                    <button type="button" data-target="#promoSlider" data-slide-to="1"></button>
                                </div>
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <div class="rec-card-container">
                                            <div class="rec-book-cover"
                                                style="background-image: url('{{ $sliderBg }}');"></div>
                                            <div class="rec-details-side">
                                                <div class="bg-blur" style="background-image: url('{{ $sliderBg }}');">
                                                </div>
                                                <div class="overlay-glass"></div>
                                                <div class="rec-texts">
                                                    <h4 class="title">The Midnight Library</h4>
                                                    <p class="year">2024</p>
                                                    <p class="desc">Jelajahi ribuan kehidupan yang bisa saja kamu jalani
                                                        dalam perpustakaan antara hidup dan mati.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="rec-card-container">
                                            <div class="rec-book-cover"
                                                style="background-image: url('{{ $sliderBg }}');">
                                            </div>
                                            <div class="rec-details-side">
                                                <div class="bg-blur" style="background-image: url('{{ $sliderBg }}');">
                                                </div>
                                                <div class="overlay-glass"></div>
                                                <div class="rec-texts">
                                                    <h4 class="title">Atomic Habits</h4>
                                                    <p class="year">2023</p>
                                                    <p class="desc">Perubahan kecil yang memberikan hasil luar biasa.
                                                        Temukan cara membangun kebiasaan baik.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-4">
                            <h6 class="text-white-50 mb-3 text-capitalize" style="font-size: 15px; letter-spacing: 1.2px;">
                                Last Checked Out</h6>
                            <div class="dashboard-col-right">
                                <div class="last-check-item">
                                    <img src="{{ asset($defaultImage) }}" class="last-check-img"
                                        onerror="this.src='{{ $sliderBg }}'">
                                    <div class="last-check-info">
                                        <h6>Title Name</h6>
                                        <p>More detailed summary here</p>
                                    </div>
                                </div>
                                <div class="last-check-item">
                                    <img src="{{ asset($defaultImage) }}" class="last-check-img"
                                        onerror="this.src='{{ $sliderBg }}'">
                                    <div class="last-check-info">
                                        <h6>Another Title</h6>
                                        <p>Brief description for the list</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4" style="background: transparent; border-radius: 12px;">

                    <div class="card-body p-0">
                        <ul class="nav nav-tabs custom-full-tabs" id="bookTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all-books" role="tab"
                                    aria-controls="all-books" aria-selected="true">All Books</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="available-tab" data-toggle="tab" href="#available" role="tab"
                                    aria-controls="available" aria-selected="false">Available</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="borrowed-tab" data-toggle="tab" href="#borrowed" role="tab"
                                    aria-controls="borrowed" aria-selected="false">My Borrowed</a>
                            </li>
                        </ul>

                        <div class="tab-content custom-tab-content-area pt-3 pb-4 px-4" id="bookTabContent">

                            <div class="tab-pane fade show active" id="all-books" role="tabpanel" aria-labelledby="all-tab">
                                <div class="bg-transparent border-0 px-0 mb-3">
                                    <h4 class="text-white mb-0">Book List</h4>
                                </div>

                                <div class="row five-cols">
                                    @forelse($books ?? [] as $book)
                                        @php
                                            /** @var \App\Models\Book $book */
                                        @endphp
                                        <div class="col-item">
                                            <x-book-card :book="$book" link="{{ route('book.show', $book->id) }}" />
                                        </div>
                                    @empty
                                        <div class="col-item">
                                            <x-book-card title="Kosong" status="not available" />
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="tab-pane fade" id="available" role="tabpanel" aria-labelledby="available-tab">
                                <div class="bg-transparent border-0 px-0 mb-3">
                                    <h4 class="text-white mb-0">Available Books</h4>
                                </div>

                                <div class="row five-cols">
                                    @forelse($books->where('status', 'avaiable') as $book)
                                        @php
                                            /** @var \App\Models\Book $book */
                                        @endphp
                                        <div class="col-item">
                                            {{-- FIX: Gunakan :book="$book" agar image diproses otomatis --}}
                                            <x-book-card :book="$book" link="{{ route('book.show', $book->id) }}" />
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-white-50">Tidak ada buku yang berstatus available saat ini.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="tab-pane fade" id="borrowed" role="tabpanel" aria-labelledby="borrowed-tab">
                                <div class="bg-transparent border-0 px-0 mb-3">
                                    <h4 class="text-white mb-0">Books I Borrowed</h4>
                                </div>

                                <div class="row five-cols">
                                    @forelse($myBorrowedBooks as $book)
                                        <div class="col-item">
                                            <x-book-card :book="$book" link="{{ route('book.show', $book->id) }}" />
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-white-50">Kamu sedang tidak meminjam buku apa pun saat ini.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function() {
            var $slider = $('#promoSlider');

            $slider.carousel({
                interval: 4000,
                pause: false
            });

            $slider.on('slide.bs.carousel', function(e) {
                var index = e.to;
                var $dots = $('.custom-indicators button');
                $dots.removeClass('active');
                $dots.eq(index).addClass('active');
            });
        });
    </script>
@endsection
