@extends('layouts.backend.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>User Management</h1>
                <div class="section-header-button">
                    {{-- Arahkan langsung ke halaman create --}}
                    <a href="{{ route('user.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Add New User
                    </a>
                </div>
            </div>

            <div class="section-body">
                <style>
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

                    .user-avatar {
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        object-fit: cover;
                        border: 2px solid #333;
                    }

                    .badge-role {
                        padding: 5px 12px;
                        border-radius: 4px;
                        font-weight: 600;
                        font-size: 10px;
                        text-transform: uppercase;
                    }

                    .role-kepala {
                        background-color: #6777ef;
                        color: white;
                    }

                    .role-petugas {
                        background-color: #28a745;
                        color: white;
                    }

                    .role-anggota {
                        background-color: #6c757d;
                        color: white;
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
                        <form action="{{ route('user.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control"
                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                    placeholder="Search Name or Email..." value="{{ request('search') }}">
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
                                        <th>Avatar</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $index => $user)
                                        <tr>
                                            {{-- Nomor urut otomatis nyesuain halaman --}}
                                            <td>{{ $users->firstItem() + $index }}</td>
                                            <td>
                                                <img src="{{ $user->image
                                                    ? (str_starts_with($user->image, 'assets')
                                                        ? asset($user->image)
                                                        : asset('storage/' . $user->image))
                                                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                                    class="user-avatar"
                                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}'">
                                            </td>
                                            <td class="font-weight-600">{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @php
                                                    $roleClass = '';
                                                    if ($user->role == 'kepala_perpustakaan') {
                                                        $roleClass = 'role-kepala';
                                                    } elseif ($user->role == 'petugas') {
                                                        $roleClass = 'role-petugas';
                                                    } else {
                                                        $roleClass = 'role-anggota';
                                                    }
                                                @endphp
                                                <span class="badge-role {{ $roleClass }}">
                                                    {{ str_replace('_', ' ', $user->role) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    {{-- Arahkan ke halaman edit --}}
                                                    <a href="{{ route('user.edit', $user->id) }}"
                                                        class="btn btn-sm btn-warning mr-1">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="fas fa-users mb-2" style="font-size: 24px;"></i><br>
                                                No users found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination Links --}}
                    <div class="card-footer bg-transparent border-0 px-0 mt-3 d-flex justify-content-end">
                        {{ $users->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
