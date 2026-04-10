 <nav class="navbar navbar-expand-lg main-navbar">
     <a href="index.html" class="navbar-brand sidebar-gone-hide">Perpustakaan</a>
     <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars"></i></a>
     <div class="nav-collapse">
         <a class="sidebar-gone-show nav-collapse-toggle nav-link" href="#">
             <i class="fas fa-ellipsis-v"></i>
         </a>
         <ul class="navbar-nav">
             <li class="nav-item active"><a href="/" class="nav-link">Home</a></li>
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
                     <img alt="image"
                         src="{{ str_starts_with(Auth::user()->image, 'assets')
                             ? asset(Auth::user()->image)
                             : asset('storage/users/' . Auth::user()->image) }}"
                         class="rounded-circle mr-1"
                         style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #dee2e6; padding: 1px; background-color: #fff;"
                         onerror="this.src='{{ asset('assets/img/avatar/avatar-1.png') }}'">
                 @else
                     {{-- Gambar default jika user belum punya foto sama sekali --}}
                     <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1"
                         style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #dee2e6; padding: 1px; background-color: #fff;">
                 @endif

                 <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div>
             </a>
             <div class="dropdown-menu dropdown-menu-right">
                 <div class="dropdown-title">Logged in 5 min ago</div>

                 <a href="{{ route('frontend.profile.index') }}" class="dropdown-item has-icon">
                     <i class="fas fa-cog"></i> Settings
                 </a>
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
