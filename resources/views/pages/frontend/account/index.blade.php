@extends('layouts.frontend.app')
{{-- @push('css.buku')
    <style>
        /* Custom CSS untuk Book Card */
        .book-card {
            width: 195px;
            height: 290px;
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .book-card:hover {
            /* transform: translateY(-5px); */
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        /* Membuat efek gelap (gradient) di bawah agar teks putih tetap terbaca */
        .book-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            z-index: 1;
        }

        /* Memastikan konten berada di atas efek gelap */
        .book-card .article-header {
            position: relative;
            height: 100%;
            z-index: 2;
        }

        /* Posisi tombol Available di kanan atas */
        .book-card .btn-available {
            position: absolute;
            top: 0;
            right: 0;
            background-color: #6777ef;
            /* Warna primary Stisla */
            color: white;
            border: none;
            border-radius: 0 10px 0 10px;
            /* Melengkung di sudut tertentu saja */
            padding: 5px 15px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Posisi judul di kiri bawah */
        .book-card .article-title {
            position: absolute;
            bottom: 15px;
            left: 15px;
            margin: 0;
        }

        .book-card .article-title h2 {
            color: white;
            font-size: 18px;
            margin: 0;
            font-weight: 700;
            line-height: 1.2;
        }
    </style>
@endpush --}}
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>User Profile</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    {{-- <div class="breadcrumb-item"><a href="#">Components</a></div> --}}
                    <div class="breadcrumb-item">Account</div>
                </div>
            </div>

            <div class="section-body">
                {{-- <h2 class="section-title">Users</h2> --}}
                {{-- <p class="section-lead">Components relating to users, lists of users and so on.</p> --}}

                <div class="row">
                    <div class="col-12 col-sm-12 col-lg-12">
                        <div class="card profile-widget">
                            <div class="profile-widget-header">
                                <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}"
                                    class="rounded-circle profile-widget-picture">
                                <div class="profile-widget-items">
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Status_Dipinjam</div>
                                        <div class="profile-widget-item-value">187</div>
                                    </div>
                                    {{-- <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Followers</div>
                                        <div class="profile-widget-item-value">6,8K</div>
                                    </div> --}}
                                    <div class="profile-widget-item">
                                        <div class="profile-widget-item-label">Status_Jatuh Tempo</div>
                                        <div class="profile-widget-item-value">2,1K</div>
                                    </div>
                                </div>
                            </div>
                            {{-- penting --}}
                            {{-- <div class="profile-widget-description pb-0">
                                <div class="profile-widget-name">Hasan Basri <div
                                        class="text-muted d-inline font-weight-normal">
                                        <div class="slash"></div> Web Developer
                                    </div>
                                </div>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                    consequat.</p>
                                <div class="section-body">
                                    <h2 class="section-title">Book List</h2>

                                    <div class="row">
                                        <div class="col-auto">
                                            <article class="article book-card"
                                                style="background-image: url('{{ asset('assets/r/557779963_3486861214786610_5644905288489553813_n (1).jpg') }}');">
                                                <div class="article-header">
                                                    <button class="btn btn-available">Available</button>
                                                    <div class="article-title">
                                                        <h2>Title</h2>
                                                    </div>
                                                </div>
                                            </article>
                                        </div>

                                        <div class="col-auto">
                                            <article class="article book-card"
                                                style="background-image: url('{{ asset('assets/r/557779963_3486861214786610_5644905288489553813_n (1).jpg') }}');">
                                                <div class="article-header">
                                                    <button class="btn btn-available">Available</button>
                                                    <div class="article-title">
                                                        <h2>Title 2</h2>
                                                    </div>
                                                </div>
                                            </article>
                                        </div>

                                        <div class="col-auto">
                                            <article class="article book-card"
                                                style="background-image: url('{{ asset('assets/r/557779963_3486861214786610_5644905288489553813_n (1).jpg') }}');">
                                                <div class="article-header">
                                                    <button class="btn btn-available">Available</button>
                                                    <div class="article-title">
                                                        <h2>Title 3</h2>
                                                    </div>
                                                </div>
                                            </article>
                                        </div>

                                    </div>
                                </div>
                            </div> --}}
                            {{-- end --}}


                        </div>
                        {{-- <div class="card mt-4">
                            <div class="card-header">
                                <h4>List Pinjaman</h4>
                            </div>
                            <div class="card-body pb-0">
                                <div class="row">
                                    <div class="col-6 col-sm-3 col-lg-3 mb-4 mb-md-0">
                                        <div class="avatar-item">
                                            <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}"
                                                class="img-fluid" data-toggle="tooltip" title="Syahdan Ubaidillah">
                                            <div class="avatar-badge" title="Editor" data-toggle="tooltip"><i
                                                    class="fas fa-wrench"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3 col-lg-3 mb-4 mb-md-0">
                                        <div class="avatar-item">
                                            <img alt="image" src="{{ asset('assets/img/avatar/avatar-2.png') }}"
                                                class="img-fluid" data-toggle="tooltip" title="Danny Stenvenson">
                                            <div class="avatar-badge" title="Admin" data-toggle="tooltip"><i
                                                    class="fas fa-cog"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3 col-lg-3 mb-4 mb-md-0">
                                        <div class="avatar-item">
                                            <img alt="image" src="{{ asset('assets/img/avatar/avatar-3.png') }}"
                                                class="img-fluid" data-toggle="tooltip" title="Riko Huang">
                                            <div class="avatar-badge" title="Author" data-toggle="tooltip"><i
                                                    class="fas fa-pencil-alt"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3 col-lg-3 mb-4 mb-md-0">
                                        <div class="avatar-item">
                                            <img alt="image" src="{{ asset('assets/img/avatar/avatar-4.png') }}"
                                                class="img-fluid" data-toggle="tooltip" title="Luthfi Hakim">
                                            <div class="avatar-badge" title="Author" data-toggle="tooltip"><i
                                                    class="fas fa-pencil-alt"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-sm-3 col-lg-3 mb-4 mb-md-0">
                                        <div class="avatar-item">
                                            <img alt="image" src="{{ asset('assets/img/avatar/avatar-5.png') }}"
                                                class="img-fluid" data-toggle="tooltip" title="Alfa Zulkarnain">
                                            <div class="avatar-badge" title="Editor" data-toggle="tooltip"><i
                                                    class="fas fa-wrench"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3 col-lg-3 mb-4 mb-md-0">
                                        <div class="avatar-item">
                                            <img alt="image" src="{{ asset('assets/img/avatar/avatar-4.png') }}"
                                                class="img-fluid" data-toggle="tooltip" title="Egi Ferdian">
                                            <div class="avatar-badge" title="Admin" data-toggle="tooltip"><i
                                                    class="fas fa-cog"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3 col-lg-3 mb-4 mb-md-0">
                                        <div class="avatar-item">
                                            <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}"
                                                class="img-fluid" data-toggle="tooltip" title="Jaka Ramadhan">
                                            <div class="avatar-badge" title="Author" data-toggle="tooltip"><i
                                                    class="fas fa-pencil-alt"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3 col-lg-3 mb-4 mb-md-0">
                                        <div class="avatar-item">
                                            <img alt="image" src="{{ asset('assets/img/avatar/avatar-2.png') }}"
                                                class="img-fluid" data-toggle="tooltip" title="Ryan">
                                            <div class="avatar-badge" title="Admin" data-toggle="tooltip"><i
                                                    class="fas fa-cog"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
