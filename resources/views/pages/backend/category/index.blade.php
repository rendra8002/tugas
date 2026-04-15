@extends('layouts.backend.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Category Management</h1>
                <div class="section-header-button">
                    <a href="{{ route('category-admin.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Add Category
                    </a>
                </div>
            </div>

            <div class="section-body">
                <style>
                    .table-dark-custom { background-color: #1a1a24; color: white; border-radius: 8px; overflow: hidden; }
                    .table-dark-custom thead th { border-bottom: 1px solid #333; color: #aaa; text-transform: uppercase; font-size: 10px; letter-spacing: 1px; padding: 15px; }
                    .table-dark-custom td { border-top: 1px solid #222; vertical-align: middle; font-size: 13px; padding: 15px; }
                    .pagination .page-link { background-color: #1a1a24; border-color: #333; color: #aaa; }
                    .pagination .page-item.active .page-link { background-color: #6F4E37; border-color: #6F4E37; color: white; }
                    .pagination .page-link:hover { background-color: #252531; color: white; }
                </style>

                <div class="card bg-transparent border-0">
                    <div class="card-header bg-transparent border-0 px-0 d-flex justify-content-end">
                        <form action="{{ route('category-admin.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" style="background-color: #252531; color: white; border: 1px solid #444;" placeholder="Search Category..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" style="background-color: #6F4E37; border: none;"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark-custom mb-0">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Category Name</th>
                                        <th class="text-center">Total Books</th>
                                        <th class="text-center" width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $index => $cat)
                                        <tr>
                                            <td>{{ $categories->firstItem() + $index }}</td>
                                            <td><div class="font-weight-600" style="font-size: 15px; color: #fff;">{{ $cat->name }}</div></td>
                                            <td class="text-center"><span class="badge badge-secondary" style="background-color: #252531; color: #aaa;">{{ $cat->books_count }} Books</span></td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('category-admin.edit', $cat->id) }}" class="btn btn-sm btn-warning mr-1 shadow-sm"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('category-admin.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kategori ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted"><i class="fas fa-folder-open mb-2" style="font-size: 24px;"></i><br>No categories found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-0 mt-3 d-flex justify-content-end">
                        {{ $categories->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection