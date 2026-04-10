@extends('layouts.frontend.app')

@section('content')
    {{-- CSS Cropper.js --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

    <style>
        /* Memastikan bingkai preview SELALU kotak 1:1 dan BESAR */
        .preview-container {
            width: 100%;
            max-width: 500px;
            aspect-ratio: 1 / 1;
            margin: 0 auto;
            background-color: #0f0f16;
            border: 1px dashed #444;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }

        #previewProfileEditFE {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
    </style>

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    {{-- Tombol Kembali ke Halaman Utama FE --}}
                    <a href="{{ route('index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Edit Profil: {{ $user->name }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card"
                            style="background-color: #1a1a24; color: white; border-radius: 12px; border: 1px solid #333;">
                            <form action="{{ route('frontend.profile.update') }}" method="POST"
                                enctype="multipart/form-data" id="profileEditFormFE">
                                @csrf
                                {{-- Hidden input untuk menyimpan hasil crop --}}
                                <input type="hidden" name="image_cropped" id="image_cropped">

                                <div class="card-body">
                                    <div class="row">
                                        {{-- Kolom Kiri: Form Data --}}
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">NAMA LENGKAP</label>
                                                <input type="text" name="name" class="form-control"
                                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                                    value="{{ old('name', $user->name) }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">ALAMAT EMAIL</label>
                                                <input type="email" name="email" class="form-control"
                                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                                    value="{{ old('email', $user->email) }}" required>
                                            </div>

                                            <hr style="border-top: 1px solid #333; margin: 30px 0;">

                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">GANTI PASSWORD</label>
                                                <input type="password" name="password" class="form-control"
                                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                                    placeholder="Isi hanya jika ingin ganti">
                                            </div>

                                            <div class="alert alert-dark mt-2"
                                                style="background-color: #0f0f16; border: none; color: #888;">
                                                <small><i class="fas fa-info-circle mr-1"></i> Biarkan kosong jika tidak
                                                    ingin mengubah password.</small>
                                            </div>
                                        </div>

                                        {{-- Kolom Kanan: Editor Foto --}}
                                        <div class="col-md-5">
                                            <div class="form-group text-center">
                                                <label class="text-muted small font-weight-bold d-block text-left"
                                                    style="letter-spacing: 1px;">FOTO PROFIL</label>

                                                <div class="custom-file mb-3 text-left">
                                                    <input type="file" class="custom-file-input" id="imageInputFE"
                                                        accept="image/*">
                                                    <label class="custom-file-label"
                                                        style="background-color: #252531; color: #aaa; border: 1px solid #444;"
                                                        for="imageInputFE">Pilih foto baru...</label>
                                                </div>

                                                <div
                                                    class="preview-container d-flex justify-content-center align-items-center">
                                                    <img id="previewProfileEditFE"
                                                        src="{{ $user->image ? (str_starts_with($user->image, 'assets') ? asset($user->image) : asset('storage/' . $user->image)) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=500&background=252531&color=fff' }}"
                                                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=500&background=252531&color=fff'"
                                                        crossorigin="anonymous">
                                                </div>

                                                {{-- Tombol Zoom dll --}}
                                                <div class="mt-3">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-dark btn-sm"
                                                            onclick="cropperFE && cropperFE.zoom(0.1)"><i
                                                                class="fas fa-search-plus"></i></button>
                                                        <button type="button" class="btn btn-dark btn-sm"
                                                            onclick="cropperFE && cropperFE.zoom(-0.1)"><i
                                                                class="fas fa-search-minus"></i></button>
                                                        <button type="button" class="btn btn-dark btn-sm"
                                                            onclick="cropperFE && cropperFE.rotate(-45)"><i
                                                                class="fas fa-undo"></i></button>
                                                        <button type="button" class="btn btn-dark btn-sm"
                                                            onclick="cropperFE && cropperFE.rotate(45)"><i
                                                                class="fas fa-redo"></i></button>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="resetEditorFE()"><i
                                                                class="fas fa-sync-alt"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer border-0 d-flex justify-content-start pb-4 pl-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2 mr-2"
                                        style="background-color: #5a67d8; border: none; font-weight: 600;">Simpan
                                        Perubahan</button>
                                    <a href="{{ route('index') }}" class="btn btn-secondary px-4 py-2"
                                        style="background-color: #2d3748; border: none; font-weight: 600;">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Script --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropperFE;
        const imageFE = document.getElementById('previewProfileEditFE');
        const inputFE = document.getElementById('imageInputFE');
        const croppedInputFE = document.getElementById('image_cropped');

        inputFE.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    if (cropperFE) cropperFE.destroy();

                    document.querySelector('.preview-container').classList.remove('d-flex',
                        'justify-content-center', 'align-items-center');

                    imageFE.src = event.target.result;
                    imageFE.style.width = '100%';
                    imageFE.style.height = 'auto';
                    imageFE.style.objectFit = 'contain';

                    initCropperFE();
                };
                reader.readAsDataURL(files[0]);

                let fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            }
        });

        function initCropperFE() {
            cropperFE = new Cropper(imageFE, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1
            });
        }

        function resetEditorFE() {
            if (cropperFE) cropperFE.reset();
        }

        document.getElementById('profileEditFormFE').addEventListener('submit', function(e) {
            if (cropperFE) {
                const canvas = cropperFE.getCroppedCanvas({
                    width: 500,
                    height: 500
                });
                croppedInputFE.value = canvas.toDataURL('image/jpeg');
            }
        });
    </script>
@endsection
