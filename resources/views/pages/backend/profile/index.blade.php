@extends('layouts.backend.app')

@section('content')
    {{-- CSS Cropper.js --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

    <style>
        /* Memastikan bingkai preview selalu kotak sempurna 1:1 */
        .preview-container {
            width: 100%;
            max-width: 350px;
            aspect-ratio: 1 / 1;
            margin: 0 auto;
            background-color: #0f0f16;
            border: 1px dashed #444;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
        }
    </style>

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('backend.home.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Edit Profil: {{ $user->name }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card"
                            style="background-color: #1a1a24; color: white; border-radius: 12px; border: 1px solid #333;">
                            <form id="profileForm" action="{{ route('profile.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Hidden input untuk menyimpan gambar hasil crop --}}
                                <input type="hidden" name="cropped_image" id="cropped_image">

                                <div class="card-body">
                                    <div class="row">
                                        {{-- Kolom Kiri: Form Data Diri --}}
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">NAMA LENGKAP</label>
                                                <input type="text" name="name" class="form-control"
                                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                                    value="{{ $user->name }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">ALAMAT EMAIL</label>
                                                <input type="email" name="email" class="form-control"
                                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                                    value="{{ $user->email }}" required>
                                            </div>

                                            <hr style="border-top: 1px solid #333; margin: 30px 0;">

                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">GANTI PASSWORD</label>
                                                <input type="password" name="password" class="form-control"
                                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                                    placeholder="Masukkan password baru (minimal 8 karakter)">
                                            </div>
                                            <div class="alert alert-dark mt-2"
                                                style="background-color: #0f0f16; border: none; color: #888;">
                                                <small><i class="fas fa-info-circle mr-1"></i> Kosongkan jika tidak ingin
                                                    mengubah password.</small>
                                            </div>
                                        </div>

                                        {{-- Kolom Kanan: Image Upload & Preview --}}
                                        <div class="col-md-5 text-center">
                                            <div class="form-group text-left">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">FOTO PROFIL</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" name="image" class="custom-file-input"
                                                        id="imageProfileEdit" accept="image/*">
                                                    <label class="custom-file-label"
                                                        style="background-color: #252531; color: #aaa; border: 1px solid #444;"
                                                        for="imageProfileEdit">Pilih foto baru...</label>
                                                </div>

                                                {{-- Area Preview Box --}}
                                                <div
                                                    class="preview-container d-flex justify-content-center align-items-center">
                                                    <img id="previewProfileEdit"
                                                        src="{{ $user->image ? (str_starts_with($user->image, 'assets') ? asset($user->image) : asset('storage/' . $user->image)) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=500&background=252531&color=fff' }}"
                                                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=500&background=252531&color=fff'"
                                                        style="width: 100%; height: 100%; object-fit: cover; display: block;"
                                                        crossorigin="anonymous">
                                                </div>

                                                {{-- Tombol Kontrol --}}
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
                                                            onclick="resetEditor()"><i class="fas fa-sync-alt"></i></button>
                                                    </div>
                                                </div>

                                                <div class="text-center mt-3">
                                                    <span class="badge badge-dark" style="background-color: #333;">ROLE:
                                                        {{ strtoupper(str_replace('_', ' ', $user->role)) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer border-0 d-flex justify-content-start pb-4 pl-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2 mr-2"
                                        style="background-color: #5a67d8; border: none; font-weight: 600;">Simpan
                                        Perubahan</button>
                                    <a href="{{ route('backend.home.index') }}" class="btn btn-secondary px-4 py-2"
                                        style="background-color: #2d3748; border: none; font-weight: 600;">Batal</a>
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
        const image = document.getElementById('previewProfileEdit');
        const fileInput = document.getElementById('imageProfileEdit');
        const form = document.getElementById('profileForm');

        fileInput.addEventListener('change', function(e) {
            const files = e.target.files;
            $(this).siblings(".custom-file-label").addClass("selected").html(files[0].name);

            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    if (cropper) cropper.destroy();

                    document.querySelector('.preview-container').classList.remove('d-flex',
                        'justify-content-center', 'align-items-center');

                    image.src = event.target.result;
                    image.style.width = '100%';
                    image.style.height = 'auto';
                    image.style.objectFit = 'contain';

                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 1
                    });
                };
                reader.readAsDataURL(files[0]);
            }
        });

        function resetEditor() {
            if (cropper) cropper.reset();
        }

        form.addEventListener('submit', function(e) {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 500,
                    height: 500
                });
                document.getElementById('cropped_image').value = canvas.toDataURL('image/jpeg');
            }
        });
    </script>
@endsection
