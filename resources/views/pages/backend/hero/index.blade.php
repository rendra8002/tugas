@extends('layouts.backend.app')

@section('content')
    {{-- Alert Toast di Pojok Kanan Bawah --}}
    @if (session('success'))
        <div id="floating-alert" class="toast-custom">
            <div class="toast-content">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Admin</a></div>
                    <div class="breadcrumb-item">Dashboard</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Selamat Datang, {{ Auth::user()->name }}!</h2>
                <p class="section-lead">Anda masuk sebagai <strong>{{ strtoupper(Auth::user()->role) }}</strong>. Kelola
                    data perpustakaan dengan mudah di sini.</p>

                {{-- Kamu bisa tambahkan statistik ringkas di sini nanti --}}
            </div>
        </section>
    </div>

    {{-- CSS Alert Simpel & Kecil --}}
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
            /* Warna hijau sukses */
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

        /* Animasi: Masuk dari kanan, lalu hilang perlahan */
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

    <script>
        // Hapus element dari DOM setelah 3 detik agar tidak menumpuk
        setTimeout(function() {
            var alert = document.getElementById('floating-alert');
            if (alert) {
                alert.remove();
            }
        }, 3000);
    </script>
@endsection
