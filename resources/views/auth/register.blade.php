<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Register &mdash; Perpustakaan</title>

    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}" />
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    {{-- Ukuran kolom sedikit lebih lebar dari login agar 2 kolom password muat rapi --}}
                    <div
                        class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">

                        {{-- STYLE CARD DISAMAKAN DENGAN LOGIN --}}
                        <div class="card mt-5" style="background-color: #282424">
                            <div class="card-header"
                                style="display: flex; justify-content: center; align-items: center;">
                                <h4 style="margin-bottom: 0;">Register Perpustakaan</h4>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('register.post') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label for="name">Nama Lengkap</label>
                                        <input id="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name') }}" autofocus required />
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email Valid</label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required />
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label for="password" class="d-block">Password</label>
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" required />
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="password_confirmation" class="d-block">Konfirmasi
                                                Password</label>
                                            <input id="password_confirmation" type="password" class="form-control"
                                                name="password_confirmation" required />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="agree"
                                                class="custom-control-input @error('agree') is-invalid @enderror"
                                                id="agree" required />
                                            <label class="custom-control-label" for="agree">Saya setuju dengan Syarat
                                                & Ketentuan</label>
                                            @error('agree')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                                            Daftar Sekarang
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="mt-5 text-muted text-center">
                            Already have an account? <a href="{{ route('login') }}">Login here</a>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
</body>

</html>
