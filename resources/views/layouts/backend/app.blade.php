<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Perpustakaan</title>

    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>

    {{-- CSS KHUSUS UNTUK TOAST ALERT GLOBAL --}}
    <style>
        .toast-custom {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 9999;
            animation: slideIn 0.5s ease-out, fadeOut 0.5s ease-in 2.5s forwards;
        }

        .toast-content {
            background: #28a745;
            /* Hijau sukses */
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            min-width: 200px;
        }

        .toast-error {
            background: #dc3545;
            /* Merah error */
        }

        /* Animasi masuk dan keluar */
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
                visibility: hidden;
            }
        }
    </style>
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">

            {{-- ALERT GLOBAL MUNCUL DI SINI --}}
            @if (session('success'))
                <div id="floating-alert-success" class="toast-custom">
                    <div class="toast-content">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div id="floating-alert-error" class="toast-custom">
                    <div class="toast-content toast-error">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif
            {{-- END ALERT GLOBAL --}}

            @include('layouts.backend.navbar')
            @include('layouts.backend.sidebar')

            @yield('content')

            @include('layouts.backend.footer')
        </div>
    </div>

    <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/modules/popper.js') }}"></script>
    <script src="{{ asset('assets/modules/tooltip.js') }}"></script>
    <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('assets/modules/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/stisla.js') }}"></script>

    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    {{-- SCRIPT UNTUK AUTO-HIDE TOAST ALERT --}}
    <script>
        // Hapus elemen alert setelah 3 detik
        setTimeout(function() {
            var alertSuccess = document.getElementById('floating-alert-success');
            if (alertSuccess) {
                alertSuccess.remove();
            }

            var alertError = document.getElementById('floating-alert-error');
            if (alertError) {
                alertError.remove();
            }
        }, 3000);
    </script>
</body>

</html>
