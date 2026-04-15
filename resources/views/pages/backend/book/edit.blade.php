@extends('layouts.backend.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('book-admin.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Edit Book: {{ $book->title }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card"
                            style="background-color: #1a1a24; color: white; border-radius: 12px; border: 1px solid #333;">
                            <form action="{{ route('book-admin.update', $book->id) }}" method="POST"
                                enctype="multipart/form-data" id="bookForm">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="image_cropped" id="image_cropped">

                                <div class="card-body">
                                    <div class="row">
                                        {{-- Kolom Kiri --}}
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold">BOOK TITLE</label>
                                                <input type="text" name="title" class="form-control"
                                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                                    value="{{ $book->title }}" required>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="text-muted small font-weight-bold">CATEGORY</label>
                                                        <select name="category_id" class="form-control"
                                                            style="background-color: #252531; color: white; border: 1px solid #444;">
                                                            @foreach ($categories as $cat)
                                                                <option value="{{ $cat->id }}"
                                                                    {{ $book->category_id == $cat->id ? 'selected' : '' }}>
                                                                    {{ $cat->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                {{-- FIX 1: KEMBALIKAN INPUT YEAR AGAR LOLOS VALIDASI CONTROLLER --}}
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="text-muted small font-weight-bold">PUBLISH
                                                            YEAR</label>
                                                        <input type="number" name="year" class="form-control"
                                                            style="background-color: #252531; color: white; border: 1px solid #444;"
                                                            value="{{ $book->year }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="text-muted small font-weight-bold">STOCK</label>
                                                        {{-- PROTEKSI MINUS --}}
                                                        <input type="number" name="stock" class="form-control"
                                                            style="background-color: #252531; color: white; border: 1px solid #444;"
                                                            value="{{ $book->stock }}" min="0"
                                                            oninput="this.value = Math.abs(this.value)" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold">AUTHOR</label>
                                                <input type="text" name="author" class="form-control"
                                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                                    value="{{ $book->author }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold">DESCRIPTION</label>
                                                <textarea name="description" class="form-control"
                                                    style="background-color: #252531; color: white; border: 1px solid #444; height: 120px;" required>{{ $book->description }}</textarea>
                                            </div>
                                        </div>

                                        {{-- Kolom Kanan Editor --}}
                                        <div class="col-md-5">
                                            <label class="text-muted small font-weight-bold">COVER EDITOR</label>
                                            <div class="custom-file mb-3">
                                                <input type="file" class="custom-file-input" id="imageInput"
                                                    accept="image/*">
                                                <label class="custom-file-label"
                                                    style="background-color: #252531; color: #aaa; border: 1px solid #444;">Change
                                                    cover (optional)</label>
                                            </div>
                                            <div class="preview-container"
                                                style="background-color: #0f0f16; border: 1px dashed #444; height: 350px; width: 240px; margin: 0 auto; border-radius: 8px; overflow: hidden;">
                                                <img id="imageEditor"
                                                    src="{{ $book->image ? (Str::startsWith($book->image, 'http') ? $book->image : asset('storage/' . $book->image)) : asset('assets/img/no-cover.png') }}"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
                                            <div class="mt-3 text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-dark btn-sm"
                                                        onclick="cropper.zoom(0.1)"><i
                                                            class="fas fa-search-plus"></i></button>
                                                    <button type="button" class="btn btn-dark btn-sm"
                                                        onclick="cropper.zoom(-0.1)"><i
                                                            class="fas fa-search-minus"></i></button>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="resetEditor()"><i class="fas fa-sync-alt"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-transparent border-0 pl-4 pb-4">
                                    <button type="submit" class="btn btn-primary px-4"
                                        style="background-color: #5a67d8; border: none;">Update Book</button>
                                    <a href="{{ route('book-admin.index') }}" class="btn btn-secondary px-4">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper;
        const imageEditor = document.getElementById('imageEditor');
        const imageInput = document.getElementById('imageInput'); // FIX 2: Samakan ID dengan HTML
        const croppedInput = document.getElementById('image_cropped');

        // Langsung inisialisasi pas load
        window.addEventListener('DOMContentLoaded', () => {
            initCropper();
        });

        imageInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    if (cropper) cropper.destroy();
                    imageEditor.src = event.target.result;
                    initCropper();
                };
                reader.readAsDataURL(files[0]);

                let fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            }
        });

        function initCropper() {
            cropper = new Cropper(imageEditor, {
                aspectRatio: 195 / 290, // Rasio cover FE
                viewMode: 3, // AUTO FILL: Memenuhi container
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        }

        function resetEditor() {
            if (cropper) cropper.reset();
        }

        document.getElementById('bookForm').addEventListener('submit', function(e) { // FIX 2: Samakan ID dengan HTML
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 600,
                    height: 892 // Sesuai rasio 195:290
                });
                croppedInput.value = canvas.toDataURL('image/jpeg', 0.9);
            }
        });
    </script>
@endsection
