<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @include('layouts.adminlte.partials.head')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .main-header .sidebar-toggle:hover {
            background-color: rgba(0,0,0,0.1) !important;
            color: #4ade80 !important;
        }

        .main-header .logo {
            width: 230px !important;
            background-color: #04392b !important;
        }

        .main-header .navbar {
            margin-left: 230px !important;
            background-image: linear-gradient(to right, #064e3b, #16a34a) !important;
            border: none;
        }

        .main-sidebar {
            width: 230px !important;
            background-color: #064e3b !important;
        }

        .content-wrapper {
            margin-left: 230px !important;
        }

        .sidebar-menu > li > a {
            color: #4ade80 !important;
        }

        .sidebar-menu > li.active > a {
            background: rgba(255,255,255,0.1) !important;
            color: #fff !important;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right;
            margin-top: -5px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    @include('layouts.adminlte.partials.header')
    @include('layouts.adminlte.partials.sidebar')

    <div class="content-wrapper">
        <section class="content-header">
            <h1 style="font-weight:700;">
                @yield('title')
            </h1>
        </section>
        <section class="content">
            @yield('content')
        </section>
    </div>
    @include('layouts.adminlte.partials.footer')
</div>

{{-- 1. Ini script bawaan AdminLTE yang berisi jQuery --}}
@include('layouts.adminlte.partials.scripts')

{{-- 2. TAMBAHKAN CDN SCRIPT DI SINI (Tepat di bawah partial scripts) --}}
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- 3. Ini tempat kode custom JavaScript dari file dashboard.blade.php disuntikkan --}}
@stack('scripts')

</body>
</html>