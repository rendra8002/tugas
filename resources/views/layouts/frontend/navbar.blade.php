 <nav class="navbar navbar-expand-lg main-navbar">
     <a href="index.html" class="navbar-brand sidebar-gone-hide">Perpustakaan</a>
     <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars"></i></a>
     <div class="nav-collapse">
         <a class="sidebar-gone-show nav-collapse-toggle nav-link" href="#">
             <i class="fas fa-ellipsis-v"></i>
         </a>
         <ul class="navbar-nav">
             <li class="nav-item active"><a href="/" class="nav-link">Home</a></li>
             <li class="nav-item"><a href="/user/profile" class="nav-link">Account</a></li>
         </ul>
     </div>
     <form class="form-inline ml-auto mr-auto" onsubmit="return false;" style="position: relative;">
         <ul class="navbar-nav">
             <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                         class="fas fa-search"></i></a></li>
         </ul>
         <div class="search-element" style="position: relative;">
             <input class="form-control" type="search" placeholder="Cari judul buku..." data-width="250"
                 id="buku-search-input" autocomplete="off">
             <button class="btn" type="button"><i class="fas fa-search"></i></button>

             {{-- DROPDOWN CUSTOM SUPER AMAN --}}
             <div id="buku-search-dropdown" class="custom-dropdown-result">
                 <div id="buku-search-content"></div>
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
                             : asset('storage/' . Auth::user()->image) }}"
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
 @push('scripts')
     <script>
         $(document).ready(function() {
             var inputCari = $('#buku-search-input');
             var dropdownHasil = $('#buku-search-dropdown');
             var kontenHasil = $('#buku-search-content');

             inputCari.on('keyup', function() {
                 var query = $(this).val();
                 console.log("Mengetik: " + query); // CEK DI CONSOLE

                 if (query.length >= 2) {
                     console.log("Memulai AJAX request..."); // CEK DI CONSOLE

                     $.ajax({
                         url: "{{ route('books.search') }}",
                         type: "GET",
                         data: {
                             'q': query
                         },
                         success: function(data) {
                             console.log("Data berhasil didapat: ", data); // CEK DI CONSOLE

                             kontenHasil.empty();
                             var html = '<div class="custom-search-header">Hasil Pencarian (' +
                                 data.length + ')</div>';

                             if (data.length > 0) {
                                 $.each(data, function(index, book) {
                                     html += `
                                <a href="${book.url}" class="custom-search-item">
                                    <img src="${book.image}" style="width: 35px; height: 50px; object-fit: cover; border-radius: 4px; margin-right: 15px;" alt="cover">
                                    <div>
                                        <div style="font-weight: 600; font-size: 13px; color: #34395e; line-height: 1.2; margin-bottom: 3px;">${book.title}</div>
                                        <div style="font-size: 11px; color: #98a6ad;">${book.author}</div>
                                    </div>
                                </a>`;
                                 });
                             } else {
                                 html +=
                                     '<div style="padding: 15px; text-align: center; color: #888; font-size: 12px;">Buku tidak ditemukan</div>';
                             }

                             kontenHasil.html(html);
                             dropdownHasil.fadeIn(200); // Tampilkan dengan animasi
                         },
                         error: function(xhr) {
                             console.error("AJAX ERROR!", xhr.responseText);
                         }
                     });
                 } else {
                     dropdownHasil.fadeOut(200);
                 }
             });

             // Sembunyikan kalau user klik area kosong di luar dropdown
             $(document).on('click', function(e) {
                 if (!$(e.target).closest('#buku-search-input, #buku-search-dropdown').length) {
                     dropdownHasil.fadeOut(200);
                 }
             });
         });
     </script>
 @endpush
