@extends('layouts.backend.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Book Management</h1>
                <div class="section-header-button">
                    <a href="{{ route('book-admin.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Add New Book
                    </a>
                </div>
            </div>

            <div class="section-body">
                <style>
                    /* Style lo tetap 100% utuh */
                    .table-dark-custom {
                        background-color: #1a1a24;
                        color: white;
                        border-radius: 8px;
                        overflow: hidden;
                    }

                    .table-dark-custom thead th {
                        border-bottom: 1px solid #333;
                        color: #aaa;
                        text-transform: uppercase;
                        font-size: 10px;
                        letter-spacing: 1px;
                        padding: 15px;
                    }

                    .table-dark-custom td {
                        border-top: 1px solid #222;
                        vertical-align: middle;
                        font-size: 13px;
                        padding: 15px;
                    }

                    /* GANTI BAGIAN INI */
                    .book-cover-sm {
                        width: 35px;
                        /* Diubah dari 45px */
                        height: 50px;
                        /* Diubah dari 65px */
                        border-radius: 3px;
                        /* Sedikit lebih lancip agar proporsional */
                        object-fit: cover;
                        border: 1px solid #333;
                        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
                        transition: all 0.3s ease;
                    }

                    .badge-status {
                        padding: 5px 12px;
                        border-radius: 4px;
                        font-weight: 600;
                        font-size: 10px;
                        text-transform: uppercase;
                    }

                    .status-available {
                        background-color: #28a745;
                        color: white;
                    }

                    .status-unavailable {
                        background-color: #dc3545;
                        color: white;
                    }

                    .stock-warning {
                        color: #ffc107;
                        font-weight: bold;
                    }

                    .stock-danger {
                        color: #ff4d4d;
                        font-weight: bold;
                    }

                    /* Custom Pagination Style untuk Dark Mode */
                    .pagination .page-link {
                        background-color: #1a1a24;
                        border-color: #333;
                        color: #aaa;
                    }

                    .pagination .page-item.active .page-link {
                        background-color: #6F4E37;
                        border-color: #6F4E37;
                        color: white;
                    }

                    .pagination .page-link:hover {
                        background-color: #252531;
                        color: white;
                    }
                </style>

                <div class="card bg-transparent border-0">
                    {{-- Form Pencarian --}}
                    <div class="card-header bg-transparent border-0 px-0 d-flex justify-content-end">
                        <form action="{{ route('book-admin.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control"
                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                    placeholder="Search Title or Author..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"
                                        style="background-color: #6F4E37; border: none;">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark-custom mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cover</th>
                                        <th>Title, Author & Year</th>
                                        <th>Category</th> {{-- TAMBAHKAN INI --}}
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($books as $index => $book)
                                        <tr>
                                            {{-- Nomor urut otomatis nyesuain halaman (1, 2.. trus lanjut 8, 9..) --}}
                                            <td>{{ $books->firstItem() + $index }}</td>
                                            <td>
                                                @php
                                                    // 1. Gambar default kalau kosong
                                                    $displayImage = 'https://via.placeholder.com/150x200?text=No+Cover';

                                                    if (!empty($book->image)) {
                                                        // 2. Baca dari DB (Link URL OpenLibrary / Internet)
                                                        if (
                                                            \Illuminate\Support\Str::startsWith($book->image, [
                                                                'http://',
                                                                'https://',
                                                            ])
                                                        ) {
                                                            $displayImage = $book->image;
                                                        }
                                                        // 3. Baca dari Seeder Lokal (Folder public/assets/r/)
                                                        elseif (
                                                            \Illuminate\Support\Str::startsWith($book->image, 'assets')
                                                        ) {
                                                            $displayImage = asset($book->image);
                                                        }
                                                        // 4. Baca dari Storage (Hasil Upload Admin)
                                                        else {
                                                            $displayImage = asset('storage/' . $book->image);
                                                        }
                                                    }
                                                @endphp

                                                {{-- Menampilkan Gambar --}}
                                                <img src="{{ $displayImage }}" class="book-cover-sm" alt="cover"
                                                    onerror="this.src='https://via.placeholder.com/150x200?text=Broken+Image'">
                                            </td>
                                            <td>
                                                <div class="font-weight-600" style="font-size: 14px; color: #fff;">
                                                    {{ $book->title }}
                                                </div>
                                                <div class="text-muted small">
                                                    By: {{ $book->author }} <span class="mx-1">|</span> Year: <span
                                                        class="text-warning">{{ $book->year ?? '-' }}</span>
                                                </div>
                                            </td>
                                            {{-- TAMBAHKAN TD INI UNTUK CATEGORY --}}
                                            <td>
                                                <span class="badge badge-dark"
                                                    style="background-color: #252531; color: #ccc; border: 1px solid #333;">
                                                    {{ $book->category->name ?? 'Uncategorized' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="{{ $book->stock <= 0 ? 'stock-danger' : ($book->stock <= 5 ? 'stock-warning' : '') }}">
                                                    {{ $book->stock }} Pcs
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge-status {{ $book->status == 'avaiable' ? 'status-available' : 'status-unavailable' }}">
                                                    {{ $book->status == 'avaiable' ? 'AVAILABLE' : 'NOT AVAILABLE' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    {{-- TOMBOL EDIT --}}
                                                    <a href="{{ route('book-admin.edit', $book->id) }}"
                                                        class="btn btn-sm btn-warning mr-1 shadow-sm" title="Edit Buku">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    {{-- TOMBOL DELETE (Selalu Aktif) --}}
                                                    <form action="{{ route('book-admin.destroy', $book->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Hapus buku ini dari perpustakaan?')">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm"
                                                            title="Hapus Buku">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="fas fa-box-open mb-2" style="font-size: 24px;"></i><br>
                                                No books found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination Links --}}
                    <div class="card-footer bg-transparent border-0 px-0 mt-3 d-flex justify-content-end">
                        {{ $books->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
