@extends('layouts.backend.app')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('category-admin.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Add Category</h1>
            </div>

            <div class="section-body">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card"
                            style="background-color: #1a1a24; color: white; border-radius: 12px; border: 1px solid #333;">
                            <form action="{{ route('category-admin.update', $category->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="text-muted small font-weight-bold"
                                            style="letter-spacing: 1px;">CATEGORY NAME</label>
                                        <input type="text" name="name" class="form-control"
                                            style="background-color: #252531; color: white; border: 1px solid #444;"
                                            placeholder="e.g. Fiction, Science, Novel..."
                                            value="{{ $category->name }}"required autofocus>
                                    </div>
                                </div>
                                <div class="card-footer border-0 d-flex justify-content-end pb-4 pr-4">
                                    <a href="{{ route('category-admin.index') }}" class="btn btn-secondary px-4 py-2 mr-2"
                                        style="background-color: #2d3748; border: none; font-weight: 600;">Cancel</a>
                                    <button type="submit" class="btn btn-primary px-4 py-2"
                                        style="background-color: #5a67d8; border: none; font-weight: 600;">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
