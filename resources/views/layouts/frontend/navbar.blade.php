 <nav class="navbar navbar-expand-lg main-navbar">
     <a href="index.html" class="navbar-brand sidebar-gone-hide">Stisla</a>
     <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars"></i></a>
     <div class="nav-collapse">
         <a class="sidebar-gone-show nav-collapse-toggle nav-link" href="#">
             <i class="fas fa-ellipsis-v"></i>
         </a>
         <ul class="navbar-nav">
             <li class="nav-item active"><a href="/home" class="nav-link">Home</a></li>
             <li class="nav-item"><a href="#" class="nav-link">Bookmark</a></li>
             <li class="nav-item"><a href="/user/profile" class="nav-link">Account</a></li>
         </ul>
     </div>
     <form class="form-inline ml-auto">
         <ul class="navbar-nav">
             <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                         class="fas fa-search"></i></a></li>
         </ul>
         <div class="search-element">
             <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="250">
             <button class="btn" type="submit"><i class="fas fa-search"></i></button>
             <div class="search-backdrop"></div>
             <div class="search-result">
             </div>
         </div>
     </form>
     <ul class="navbar-nav navbar-right">
         <li class="dropdown"><a href="#" data-toggle="dropdown"
                 class="nav-link dropdown-toggle nav-link-lg nav-link-user">

                 @if (Auth::user()->image)
                     {{-- Jika user memiliki gambar, tampilkan dari storage --}}
                     <img alt="image" src="{{ asset('storage/' . Auth::user()->image) }}"
                         class="rounded-circle mr-1">
                 @else
                     {{-- Jika nullable, tampilkan gambar default Stisla. 
         (DI SINI YANG SEBELUMNYA KURANG FUNGSI asset() ) --}}
                     <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}"
                         class="rounded-circle mr-1">
                 @endif

                 <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div>
             </a>
             <div class="dropdown-menu dropdown-menu-right">
                 <div class="dropdown-title">Logged in 5 min ago</div>

                 <a href="#" class="dropdown-item has-icon text-danger"
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                     <i class="fas fa-sign-out-alt"></i> Logout
                 </a>

                 <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                     @csrf
                 </form>
             </div>
         </li>
     </ul>
 </nav>
