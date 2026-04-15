@extends('layouts.backend.app')

@section('content')
    {{-- Tambahin CSS Cropper.js di head --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('book-admin.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Add New Book</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card"
                            style="background-color: #1a1a24; color: white; border-radius: 12px; border: 1px solid #333;">
                            <form action="{{ route('book-admin.store') }}" method="POST" enctype="multipart/form-data"
                                id="bookForm">
                                @csrf

                                {{-- INPUT HIDDEN UNTUK NYIMPEN HASIL EDITAN --}}
                                <input type="hidden" name="image_cropped" id="image_cropped">

                                <div class="card-body">
                                    <div class="row">
                                        {{-- Kolom Kiri: Form Data --}}
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">BOOK TITLE</label>
                                                <input type="text" name="title" class="form-control"
                                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                                    placeholder="Enter book title" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">AUTHOR</label>
                                                <input type="text" name="author" class="form-control"
                                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                                    placeholder="Enter author name" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-muted small font-weight-bold"
                                                            style="letter-spacing: 1px;">PUBLISH YEAR</label>
                                                        <input type="number" name="year" class="form-control"
                                                            style="background-color: #252531; color: white; border: 1px solid #444;"
                                                            placeholder="e.g. 2024" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-muted small font-weight-bold"
                                                            style="letter-spacing: 1px;">STOCK</label>
                                                        {{-- Tambahkan min="0" dan oninput untuk proteksi ekstra di FE --}}
                                                        <input type="number" name="stock" class="form-control"
                                                            style="background-color: #252531; color: white; border: 1px solid #444;"
                                                            placeholder="0" value="0" min="0"
                                                            oninput="this.value = Math.abs(this.value)" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">DESCRIPTION / SYNOPSIS</label>
                                                <textarea name="description" class="form-control"
                                                    style="background-color: #252531; color: white; border: 1px solid #444; height: 150px;"
                                                    placeholder="Enter book description here..." required></textarea>
                                            </div>
                                        </div>

                                        {{-- Kolom Kanan: Image Upload & Editor --}}
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">BOOK COVER EDITOR</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="imageInput"
                                                        accept="image/*" required>
                                                    <label class="custom-file-label"
                                                        style="background-color: #252531; color: #aaa; border: 1px solid #444;"
                                                        for="imageInput">Choose cover</label>
                                                </div>

                                                {{-- Area Editor Fix Ratio --}}
                                                <div class="preview-container"
                                                    style="background-color: #0f0f16; border: 1px dashed #444; height: 400px; width: 270px; margin: 0 auto; border-radius: 8px; overflow: hidden; position: relative;">
                                                    {{-- Gunakan asset no-cover yang sama dengan FE sebagai default --}}
                                                    <img id="imageEditor" src="{{ asset('assets/img/no-cover.png') }}"
                                                        onerror="this.src='https://via.placeholder.com/600x800?text=No+Cover'"
                                                        style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                                </div>

                                                {{-- Tombol Kontrol Gaya user.edit --}}
                                                <div class="mt-3 d-flex justify-content-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-dark btn-sm"
                                                            onclick="cropper && cropper.zoom(0.1)"><i
                                                                class="fas fa-search-plus"></i></button>
                                                        <button type="button" class="btn btn-dark btn-sm"
                                                            onclick="cropper && cropper.zoom(-0.1)"><i
                                                                class="fas fa-search-minus"></i></button>
                                                        <button type="button" class="btn btn-dark btn-sm"
                                                            onclick="cropper && cropper.rotate(-45)"><i
                                                                class="fas fa-undo"></i></button>
                                                        <button type="button" class="btn btn-dark btn-sm"
                                                            onclick="cropper && cropper.rotate(45)"><i
                                                                class="fas fa-redo"></i></button>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="resetEditor()"><i
                                                                class="fas fa-sync-alt"></i></button>
                                                    </div>
                                                </div>
                                                <small class="text-muted d-block text-center mt-2">Geser/Zoom foto untuk
                                                    menyesuaikan posisi cover.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer border-0 d-flex justify-content-start pb-4 pl-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2 mr-2"
                                        style="background-color: #5a67d8; border: none; font-weight: 600;">Save
                                        Book</button>
                                    <a href="{{ route('book-admin.index') }}" class="btn btn-secondary px-4 py-2"
                                        style="background-color: #2d3748; border: none; font-weight: 600;">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper;
        const imageEditor = document.getElementById('imageEditor');
        const imageInput = document.getElementById('imageInput');
        const croppedInput = document.getElementById('image_cropped');

        imageInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    if (cropper) {
                        cropper.destroy();
                    }
                    imageEditor.src = event.target.result;
                    // Hilangkan object-fit cover bawaan preview pas cropper aktif
                    imageEditor.style.objectFit = 'fill';
                    initCropper();
                };
                reader.readAsDataURL(files[0]);

                // Update label file
                let fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            }
        });

        function initCropper() {
            cropper = new Cropper(imageEditor, {
                aspectRatio: 195 / 290, // Rasio cover buku FE
                viewMode: 3, // AUTO FILL: Pastikan tidak ada space kosong
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: false,
                cropBoxResizable: false,
                toggleDragModeOnDblclick: false,
            });
        }

        function resetEditor() {
            if (cropper) cropper.reset();
        }

        // Handle Submit
        document.getElementById('bookForm').addEventListener('submit', function(e) {
            // Kita kasih toleransi: kalau user ganti gambar, kita ambil hasil crop-nya
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 600,
                    height: 892
                });
                croppedInput.value = canvas.toDataURL('image/jpeg', 0.9);
            }
            // Kalau cropper belum ada (user belum pilih file), 
            // browser bakal handle via attribute 'required' di input file.
        });
    </script>
@endsection
