@extends('layouts.backend.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('user.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Add New User</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card"
                            style="background-color: #1a1a24; color: white; border-radius: 12px; border: 1px solid #333;">
                            <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data"
                                id="userForm">
                                @csrf
                                <input type="hidden" name="cropped_image" id="cropped_image"
                                    value="{{ old('cropped_image') }}">

                                <div class="card-body">
                                    <div class="row">
                                        {{-- Kolom Kiri: Form Data --}}
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">FULL NAME</label>
                                                <input type="text" name="name" id="nameInput"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                                    placeholder="Enter full name" value="{{ old('name') }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">EMAIL ADDRESS</label>
                                                <input type="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                                    placeholder="email@example.com" value="{{ old('email') }}" required>
                                                @error('email')
                                                    <div class="invalid-feedback text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-muted small font-weight-bold"
                                                            style="letter-spacing: 1px;">PASSWORD</label>
                                                        <input type="password" name="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            style="background-color: #252531; color: white; border: 1px solid #444;"
                                                            placeholder="Min. 6 characters" required>
                                                        @error('password')
                                                            <div class="invalid-feedback text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-muted small font-weight-bold"
                                                            style="letter-spacing: 1px;">ROLE</label>
                                                        <select name="role" class="form-control"
                                                            style="background-color: #252531; color: white; border: 1px solid #444;">
                                                            <option value="anggota"
                                                                {{ old('role') == 'anggota' ? 'selected' : '' }}>Anggota
                                                            </option>
                                                            <option value="petugas"
                                                                {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas
                                                            </option>
                                                            <option value="kepala_perpustakaan"
                                                                {{ old('role') == 'kepala_perpustakaan' ? 'selected' : '' }}>
                                                                Kepala Perpustakaan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Kolom Kanan: Image Upload & Editor --}}
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">PROFILE PHOTO EDITOR</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="imageInput"
                                                        accept="image/*">
                                                    <label class="custom-file-label"
                                                        style="background-color: #252531; color: #aaa; border: 1px solid #444;"
                                                        for="imageInput">Choose photo</label>
                                                </div>

                                                {{-- PERBAIKAN: Ditambah class d-flex justify-content-center align-items-center agar gambar ke tengah --}}
                                                <div class="preview-container d-flex justify-content-center align-items-center"
                                                    style="background-color: #0f0f16; border: 1px dashed #444; height: 350px; border-radius: 8px; overflow: hidden; position: relative;">

                                                    {{-- PERBAIKAN: Ditambah &size=500 pada URL --}}
                                                    <img id="imageEditor"
                                                        src="{{ old('cropped_image') ? old('cropped_image') : 'https://ui-avatars.com/api/?name=' . urlencode(old('name', 'New User')) . '&background=252531&color=fff&size=500' }}"
                                                        style="max-width: 100%; max-height: 100%; display: block;">
                                                </div>

                                                <div class="mt-3 d-flex justify-content-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-dark btn-sm"
                                                            onclick="cropper && cropper.zoom(0.1)" title="Zoom In"><i
                                                                class="fas fa-search-plus"></i></button>
                                                        <button type="button" class="btn btn-dark btn-sm"
                                                            onclick="cropper && cropper.zoom(-0.1)" title="Zoom Out"><i
                                                                class="fas fa-search-minus"></i></button>
                                                        <button type="button" class="btn btn-dark btn-sm"
                                                            onclick="cropper && cropper.rotate(-45)" title="Rotate Left"><i
                                                                class="fas fa-undo"></i></button>
                                                        <button type="button" class="btn btn-dark btn-sm"
                                                            onclick="cropper && cropper.rotate(45)"
                                                            title="Rotate Right"><i class="fas fa-redo"></i></button>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="resetEditor()" title="Reset"><i
                                                                class="fas fa-sync-alt"></i></button>
                                                    </div>
                                                </div>
                                                <small class="text-muted d-block text-center mt-2">Geser/Zoom foto untuk
                                                    menyesuaikan posisi profil.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer border-0 d-flex justify-content-start pb-4 pl-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2 mr-2"
                                        style="background-color: #5a67d8; border: none; font-weight: 600;">Save
                                        User</button>
                                    <a href="{{ route('user.index') }}" class="btn btn-secondary px-4 py-2"
                                        style="background-color: #2d3748; border: none; font-weight: 600;">Cancel</a>
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
        const imageInput = document.getElementById('imageInput');
        const croppedInput = document.getElementById('cropped_image');
        const nameInput = document.getElementById('nameInput');

        // Inisialisasi otomatis jika ada old('cropped_image') dari validasi yang gagal
        window.addEventListener('DOMContentLoaded', () => {
            if (croppedInput.value) {
                initCropper();
            }
        });

        // Ganti avatar UI secara live saat mengetik nama
        nameInput.addEventListener('input', function() {
            if (!cropper && !croppedInput.value) {
                let nameVal = this.value.trim() || 'New User';
                // PERBAIKAN: Ditambah &size=500
                imageEditor.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(nameVal) +
                    '&background=252531&color=fff&size=500';
            }
        });

        // Script Upload & Cropper
        imageInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    if (cropper) cropper.destroy();
                    // Paksa class d-flex hilang saat inisialisasi cropper agar canvas tidak error
                    document.querySelector('.preview-container').classList.remove('d-flex',
                        'justify-content-center', 'align-items-center');

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
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
            });
        }

        function resetEditor() {
            if (cropper) {
                cropper.reset();
            } else {
                // Reset kembali ke avatar default jika dihapus
                croppedInput.value = '';
                imageInput.value = '';
                $(imageInput).siblings(".custom-file-label").removeClass("selected").html("Choose photo");
                let nameVal = nameInput.value.trim() || 'New User';

                // Kembalikan class d-flex agar di tengah lagi
                document.querySelector('.preview-container').classList.add('d-flex', 'justify-content-center',
                    'align-items-center');

                // PERBAIKAN: Ditambah &size=500
                imageEditor.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(nameVal) +
                    '&background=252531&color=fff&size=500';
            }
        }

        document.getElementById('userForm').addEventListener('submit', function(e) {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 500,
                    height: 500
                });
                const base64data = canvas.toDataURL('image/png');
                document.getElementById('cropped_image').value = base64data;
            }
        });
    </script>
@endsection
