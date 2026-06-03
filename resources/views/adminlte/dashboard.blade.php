@extends('layouts.adminlte')

@section('title', 'Dashboard')

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner"><h3>24</h3><p>Pesanan Baru</p></div>
                <div class="icon"><i class="fa fa-shopping-cart"></i></div>
                <a href="#" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner"><h3>18</h3><p>Produk Aktif</p></div>
                <div class="icon"><i class="fa fa-heartbeat"></i></div>
                <a href="#" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner"><h3>7</h3><p>Pelanggan</p></div>
                <div class="icon"><i class="fa fa-users"></i></div>
                <a href="#" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner"><h3>3</h3><p>Stok Menipis</p></div>
                <div class="icon"><i class="fa fa-warning"></i></div>
                <a href="#" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div class="box">
        <div class="box-header with-border"><h3 class="box-title">Ringkasan Warung</h3></div>
        <div class="box-body">Tema AdminLTE sudah siap dipakai. Ganti isi halaman ini dengan konten aplikasi.</div>
    </div>
@endsection
