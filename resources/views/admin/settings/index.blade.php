@extends('layouts.adminlte')

@section('title', 'Pengaturan Website')

@section('content')
<style>
    .preview-container { background: #f8fafc; padding: 15px; border-radius: 10px; border: 2px dashed #cbd5e1; margin-top: 10px; }
    .img-preview { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; }
    .label-green { color: #064e3b; font-weight: 700; text-transform: uppercase; font-size: 13px; margin-bottom: 10px; display: block; }
    .btn-save { background-color: #064e3b; color: white; padding: 12px 30px; font-weight: 600; border-radius: 5px; border: none; }
    .btn-save:hover { background-color: #053f30; color: white; }
    .section-title { border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 20px; color: #064e3b; font-weight: 800; }
</style>

<div class="row">
    <div class="col-md-12">
        <!-- Notifikasi Auto-Hide -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible auto-hide" id="success-alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
                {{ session('success') }}
            </div>
        @endif

        <div class="box box-solid shadow">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="box-body" style="padding: 30px;">
                    
                    <!-- BAGIAN 1: INFORMASI UMUM -->
                    <h3 class="section-title"><i class="fa fa-cog"></i> Informasi Umum</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="label-green">Nama Aplikasi / Resto</label>
                                <input type="text" name="app_name" class="form-control input-lg" 
                                       value="{{ $settings['app_name'] ?? 'Posyandu Jentik' }}">
                            </div>
                        </div>
                    </div>

                    <br>

                    <!-- BAGIAN 2: PENGATURAN BANNER -->
                    <h3 class="section-title"><i class="fa fa-image"></i> Pengaturan Visual Banner</h3>
                    <div class="row">
                        <!-- Banner Beranda -->
                        <div class="col-md-6">
                            <div class="well" style="background: white;">
                                <label class="label-green"><i class="fa fa-home"></i> Banner Halaman Beranda</label>
                                <div class="form-group">
                                    <label>Judul Teks Banner</label>
                                    <input type="text" name="title_banner_home" class="form-control" 
                                           value="{{ $settings['title_banner_home'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label>File Gambar</label>
                                    <input type="file" name="banner_home" class="form-control" onchange="readURL(this, 'preview_home')">
                                    <div class="preview-container text-center">
                                        <img id="preview_home" 
                                             src="{{ isset($settings['banner_home_path']) ? asset('uploads/settings/'.$settings['banner_home_path']) : asset('images/default-banner.jpg') }}" 
                                             class="img-preview">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Banner Menu -->
                        <div class="col-md-6">
                            <div class="well" style="background: white;">
                                <label class="label-green"><i class="fa fa-cutlery"></i> Banner Halaman Menu</label>
                                <div class="form-group">
                                    <label>Judul Teks Banner</label>
                                    <input type="text" name="title_banner_menu" class="form-control" 
                                           value="{{ $settings['title_banner_menu'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label>File Gambar</label>
                                    <input type="file" name="banner_menu" class="form-control" onchange="readURL(this, 'preview_menu')">
                                    <div class="preview-container text-center">
                                        <img id="preview_menu" 
                                             src="{{ isset($settings['banner_menu_path']) ? asset('uploads/settings/'.$settings['banner_menu_path']) : asset('images/default-banner.jpg') }}" 
                                             class="img-preview">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer text-right" style="padding: 20px 30px; background: #f8fafc;">
                    <button type="submit" class="btn-save shadow">
                        <i class="fa fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview Gambar
    function readURL(input, id_target) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#' + id_target).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Auto-hide notifikasi dalam 3 detik (3000 ms)
    $(document).ready(function() {
        setTimeout(function() {
            $("#success-alert").fadeOut('slow');
        }, 3000);
    });
</script>
@endpush
@endsection
