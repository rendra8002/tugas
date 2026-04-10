@extends('layouts.backend.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('user.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Edit User</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card"
                            style="background-color: #1a1a24; color: white; border-radius: 12px; border: 1px solid #333;">
                            <form action="{{ route('user.update', $user->id) }}" method="POST"
                                enctype="multipart/form-data" id="userEditForm">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="cropped_image" id="cropped_image">

                                <div class="card-body">
                                    <div class="row">
                                        {{-- Kolom Kiri --}}
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">FULL NAME</label>
                                                <input type="text" name="name" class="form-control"
                                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                                    value="{{ old('name', $user->name) }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">EMAIL ADDRESS</label>
                                                <input type="email" name="email" class="form-control"
                                                    style="background-color: #252531; color: white; border: 1px solid #444;"
                                                    value="{{ old('email', $user->email) }}" required>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-muted small font-weight-bold"
                                                            style="letter-spacing: 1px;">PASSWORD (Blank if no
                                                            change)</label>
                                                        <input type="password" name="password" class="form-control"
                                                            style="background-color: #252531; color: white; border: 1px solid #444;"
                                                            placeholder="Enter new password">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-muted small font-weight-bold"
                                                            style="letter-spacing: 1px;">ROLE</label>
                                                        <select name="role" class="form-control"
                                                            style="background-color: #252531; color: white; border: 1px solid #444;">
                                                            <option value="anggota"
                                                                {{ $user->role == 'anggota' ? 'selected' : '' }}>Anggota
                                                            </option>
                                                            <option value="petugas"
                                                                {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas
                                                            </option>
                                                            <option value="kepala_perpustakaan"
                                                                {{ $user->role == 'kepala_perpustakaan' ? 'selected' : '' }}>
                                                                Kepala Perpustakaan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Kolom Kanan --}}
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="text-muted small font-weight-bold"
                                                    style="letter-spacing: 1px;">PHOTO EDITOR</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="imageInput"
                                                        accept="image/*">
                                                    <label class="custom-file-label"
                                                        style="background-color: #252531; color: #aaa; border: 1px solid #444;"
                                                        for="imageInput">Change photo</label>
                                                </div>

                                                <div class="preview-container"
                                                    style="background-color: #0f0f16; border: 1px dashed #444; height: 350px; border-radius: 8px; overflow: hidden; position: relative;">

                                                    {{-- PERBAIKAN: Style object-fit: cover & size=500 untuk UI Avatars --}}
                                                    <img id="imageEditor"
                                                        src="{{ $user->image ? (str_starts_with($user->image, 'assets') ? asset($user->image) : asset('storage/' . $user->image)) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=500&background=252531&color=fff' }}"
                                                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=500&background=252531&color=fff'"
                                                        style="width: 100%; height: 100%; object-fit: cover; display: block;">

                                                </div>

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
                                                <small class="text-muted d-block text-center mt-2">Geser/Zoom foto untuk
                                                    menyesuaikan posisi profil.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer border-0 d-flex justify-content-start pb-4 pl-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2 mr-2"
                                        style="background-color: #5a67d8; border: none; font-weight: 600;">Update
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
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1
            });
        }

        function resetEditor() {
            if (cropper) cropper.reset();
        }

        document.getElementById('userEditForm').addEventListener('submit', function(e) {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 500,
                    height: 500
                });
                croppedInput.value = canvas.toDataURL('image/jpeg');
            }
        });
    </script>
@endsection
