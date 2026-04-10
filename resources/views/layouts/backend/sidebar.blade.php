    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand" style="background-color: #6F4E37">
                <a href="index.html" style="font-size: 20px;">
                    Perpustakaan
                </a>
            </div>
            <div class="sidebar-brand sidebar-brand-sm">
                <a href="index.html">P</a>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-header">Manage</li>

                {{-- Home: Semua Role Bisa Lihat --}}
                <li>
                    <a class="nav-link" href="{{ route('backend.home.index') }}">
                        <i class="far fa-square"></i> <span>Home</span>
                    </a>
                </li>

                {{-- User: Semua Role Bisa Lihat --}}
                <li>
                    <a class="nav-link" href="{{ route('user.index') }}">
                        <i class="far fa-square"></i> <span>User</span>
                    </a>
                </li>

                {{-- Buku: Hanya Petugas yang Bisa Lihat --}}
                @if (Auth::user()->role === 'petugas')
                    <li>
                        <a class="nav-link" href="{{ route('book-admin.index') }}">
                            <i class="far fa-square"></i> <span>Buku</span>
                        </a>
                    </li>
                @endif

                {{-- Peminjaman: Semua Role Bisa Lihat --}}
                <li>
                    <a class="nav-link" href="{{ route('peminjaman.index') }}">
                        <i class="far fa-square"></i> <span>Peminjaman</span>
                    </a>
                </li>

                <li>
                    <a class="nav-link" href="{{ route('reports.index') }}">
                        <i class="far fa-square"></i> <span>Laporan</span>
                    </a>
                </li>
            </ul>
        </aside>
    </div>
