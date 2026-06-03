@extends('layouts.adminlte')

@section('title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box shadow-sm" style="border-top: 3px solid #064e3b; border-radius: 8px;">
            <div class="box-header with-border">
                <h3 class="box-title" style="font-weight: 700; color: #064e3b;">
                    <i class="fa fa-edit"></i> Ubah Data User
                </h3>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.users.partials.form')

                <div class="box-footer" style="background-color: #fcfcfc; padding: 15px;">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-default btn-flat">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-flat pull-right" style="background-color: #064e3b; color: white;">
                        <i class="fa fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection