@extends('layouts.frontend.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Welcome, {{ Auth::user()->name ?? 'User' }}!</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Koleksi Buku</div>
                </div>
            </div>

            <div class="section-body">
                @php
                    $defaultImage = 'assets/r/557779963_3486861214786610_5644905288489553813_n (1).jpg';
                @endphp

                <style>
                    /* --- Section Recommendation (Kiri) --- */
                    .rec-card-container {
                        width: 520px;
                        /* Diperbesar dari 450px */
                        height: 220px;
                        /* Diperbesar dari 183px */
                        position: relative;
                        border-radius: 18px;
                        border: 1px solid rgba(255, 255, 255, 0.1);
                        overflow: hidden;
                        display: flex;
                        background: #1a1a1d;
                    }

                    .rec-book-cover {
                        width: 160px;
                        /* Thumbnail diperbesar */
                        height: 100%;
                        background-size: cover;
                        background-position: center;
                        z-index: 5;
                        position: relative;
                        border-right: 1px solid rgba(255, 255, 255, 0.05);
                    }

                    .rec-details-side {
                        flex: 1;
                        position: relative;
                        overflow: hidden;
                        display: flex;
                        align-items: center;
                        padding: 25px;
                        /* Padding diperlebar */
                    }

                    .rec-details-side .bg-blur {
                        position: absolute;
                        inset: 0;
                        background-size: cover;
                        background-position: center;
                        filter: blur(25px) brightness(0.4);
                        transform: scale(1.5);
                        z-index: 1;
                    }

                    .rec-details-side .overlay-glass {
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
                        /* Font diperbesar */
                        font-weight: 700;
                        margin: 0;
                        letter-spacing: 0.5px;
                    }

                    .rec-texts .year {
                        font-size: 14px;
                        color: rgba(255, 255, 255, 0.5);
                        margin-bottom: 12px;
                    }

                    .rec-texts .desc {
                        font-size: 13px;
                        /* Teks lebih mudah dibaca */
                        color: rgba(255, 255, 255, 0.7);
                        line-height: 1.6;
                        display: -webkit-box;
                        -webkit-line-clamp: 3;
                        -webkit-box-orient: vertical;
                        overflow: hidden;
                    }

                    /* --- Section Last Checked Out (Kanan) --- */
                    .dashboard-col-right {
                        display: flex;
                        flex-direction: column;
                        gap: 14px;
                        /* Gap disesuaikan agar pas dengan tinggi kiri */
                        width: 520px;
                        /* Sama dengan lebar kiri */
                    }

                    .last-check-item {
                        background: #1e1e1e;
                        border-radius: 14px;
                        /* padding: 15px 20px; */
                        display: flex;
                        align-items: center;
                        width: 100%;
                        height: 103px;
                        /* (103*2) + 14 gap = 220px (Simetris sempurna) */
                        transition: 0.3s ease;
                        border: 1px solid rgba(255, 255, 255, 0.03);
                    }

                    .last-check-item:hover {
                        background: #252525;
                        /* transform: translateX(8px); */
                    }

                    .last-check-img {
                        width: 75px;
                        /* Thumbnail list diperbesar */
                        height: 100px;
                        border-radius: 8px;
                        object-fit: cover;
                        margin-right: 20px;
                    }

                    .last-check-info h6 {
                        color: #fff;
                        font-size: 16px;
                        /* Font judul list diperbesar */
                        margin: 0;
                        font-weight: 600;
                    }

                    .last-check-info p {
                        color: #888;
                        font-size: 13px;
                        margin-top: 4px;
                    }
                </style>

                <div class="container-fluid px-4 mt-4">
                    <div class="row dashboard-top-section">

                        <div class="col-lg-6 mb-4">
                            <h6 class="text-white-50 mb-3 text-capitalize" style="font-size: 15px; letter-spacing: 1.2px;">
                                Recommendation</h6>

                            <div class="rec-card-container shadow-lg">
                                <div class="rec-book-cover" style="background-image: url('{{ asset($defaultImage) }}');">
                                </div>

                                <div class="rec-details-side">
                                    <div class="bg-blur" style="background-image: url('{{ asset($defaultImage) }}');"></div>
                                    <div class="overlay-glass"></div>

                                    <div class="rec-texts">
                                        <h4 class="title">Title</h4>
                                        <p class="year">2024</p>
                                        <p class="desc">
                                            This is a larger description area. The layout is now wider and taller to provide
                                            a more premium feel, while maintaining the same ratio as your original design.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-4">
                            <h6 class="text-white-50 mb-3 text-capitalize" style="font-size: 15px; letter-spacing: 1.2px;">
                                last checked out</h6>

                            <div class="dashboard-col-right">
                                <div class="last-check-item">
                                    <img src="{{ asset($defaultImage) }}" class="last-check-img" alt="Book Title">
                                    <div class="last-check-info">
                                        <h6>Title Name</h6>
                                        <p>More detailed summary here</p>
                                    </div>
                                </div>

                                <div class="last-check-item">
                                    <img src="{{ asset($defaultImage) }}" class="last-check-img" alt="Book Title">
                                    <div class="last-check-info">
                                        <h6>Another Great Title</h6>
                                        <p>Brief description for the list</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>  

                <div class="card">
                    <div class="card-header">
                        <h4>Book List</h4>
                    </div>
                    <div class="card-body">
                        {{-- Tambahkan class custom 'five-cols' --}}
                        <div class="row five-cols">
                            @forelse($books as $book)
                                <div class="col-item">
                                    <x-book-card :title="$book->title" :status="$book->status" {{-- Ini akan otomatis 'not-avaiable' jika stok 0 --}}
                                        :image="asset($book->image)" />
                                </div>
                            @empty
                                {{-- Data dummy --}}
                                <div class="col-item"><x-book-card title="Laskar Pelangi" status="not avaiable" /></div>
                                <div class="col-item"><x-book-card /></div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
