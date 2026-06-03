from pathlib import Path
import argparse
import os
import stat
import shutil
import zipfile

LAYOUT = r'''<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.adminlte.partials.head')
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    @include('layouts.adminlte.partials.header')
    @include('layouts.adminlte.partials.sidebar')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@yield('title', config('app.name', 'Laravel'))</h1>
            @yield('breadcrumb')
        </section>
        <section class="content">
            @yield('content')
        </section>
    </div>

    @include('layouts.adminlte.partials.footer')
</div>
@include('layouts.adminlte.partials.scripts')
@stack('scripts')
</body>
</html>
'''

HEAD = r'''<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/all.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    @stack('styles')
</head>
'''

HEADER = r'''<header class="main-header">
    <a href="{{ url('/') }}" class="logo">
        <span class="logo-mini"><b>{{ strtoupper(substr(config('app.name', 'L'), 0, 1)) }}</b></span>
        <span class="logo-lg"><b>{{ config('app.name', 'Laravel') }}</b></span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                @auth
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <p>{{ auth()->user()->name }}<small>{{ auth()->user()->email }}</small></p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-right">
                                    @if (Route::has('logout'))
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-default btn-flat">Logout</button>
                                        </form>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    </li>
                @else
                    @if (Route::has('login'))
                        <li><a href="{{ route('login') }}">Login</a></li>
                    @endif
                    @if (Route::has('register'))
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @endif
                @endauth
            </ul>
        </div>
    </nav>
</header>
'''

SIDEBAR = r'''<aside class="main-sidebar">
    <section class="sidebar">
        @auth
            <div class="user-panel">
                <div class="pull-left image">
                    <i class="fa fa-user-circle fa-2x text-white"></i>
                </div>
                <div class="pull-left info">
                    <p>{{ auth()->user()->name }}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
        @endauth
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU UTAMA</li>
            <li class="{{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>
            <li><a href="#"><i class="fa fa-shopping-basket"></i> <span>Produk</span></a></li>
            <li><a href="#"><i class="fa fa-cutlery"></i> <span>Pesanan</span></a></li>
            <li><a href="#"><i class="fa fa-users"></i> <span>Pelanggan</span></a></li>
            <li><a href="#"><i class="fa fa-bar-chart"></i> <span>Laporan</span></a></li>
        </ul>
    </section>
</aside>
'''

FOOTER = r'''<footer class="main-footer">
    <div class="pull-right hidden-xs">AdminLTE Theme</div>
    <strong>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}.</strong> All rights reserved.
</footer>
'''

SCRIPTS = r'''<script src="{{ asset('vendor/adminlte/js/app.js') }}"></script>
'''

DEMO = r'''@extends('layouts.adminlte')

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
                <div class="icon"><i class="fa fa-cutlery"></i></div>
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
'''

FILES = {
    'resources/views/layouts/adminlte.blade.php': LAYOUT,
    'resources/views/layouts/adminlte/partials/head.blade.php': HEAD,
    'resources/views/layouts/adminlte/partials/header.blade.php': HEADER,
    'resources/views/layouts/adminlte/partials/sidebar.blade.php': SIDEBAR,
    'resources/views/layouts/adminlte/partials/footer.blade.php': FOOTER,
    'resources/views/layouts/adminlte/partials/scripts.blade.php': SCRIPTS,
    'resources/views/adminlte/dashboard.blade.php': DEMO,
}


def safe_rmtree(path: Path) -> None:
    def on_error(func, file_path, _exc_info):
        os.chmod(file_path, stat.S_IREAD | stat.S_IWRITE)
        func(file_path)

    if path.exists():
        try:
            shutil.rmtree(path, onerror=on_error)
        except PermissionError:
            shutil.rmtree(path, ignore_errors=True)


def find_root(extract_dir: Path) -> Path:
    children = [p for p in extract_dir.iterdir() if p.is_dir()]
    if len(children) == 1 and (children[0] / 'public').exists():
        return children[0]
    return extract_dir


def copy_tree(src: Path, dst: Path, force: bool) -> None:
    if dst.exists() and force:
        shutil.rmtree(dst)
    shutil.copytree(src, dst, dirs_exist_ok=True)


def copy_assets_from_folder(source: Path, vendor: Path, force: bool) -> None:
    asset_roots = {'css', 'js', 'fonts', 'img'}
    public = source / 'public'
    if not public.exists():
        raise SystemExit(f'Source folder does not contain public assets: {public}')
    if vendor.exists() and force:
        safe_rmtree(vendor)

    copied = False
    for name in asset_roots:
        src = public / name
        if src.exists():
            copy_tree(src, vendor / name, force)
            copied = True
    if not copied:
        raise SystemExit(f'No AdminLTE asset folders found under: {public}')


def copy_assets_from_zip(zip_path: Path, vendor: Path, force: bool) -> None:
    asset_roots = {'css', 'js', 'fonts', 'img'}
    if vendor.exists() and force:
        safe_rmtree(vendor)

    with zipfile.ZipFile(zip_path) as archive:
        for info in archive.infolist():
            parts = Path(info.filename).parts
            if 'public' not in parts:
                continue
            public_index = parts.index('public')
            rel_parts = parts[public_index + 1:]
            if not rel_parts or rel_parts[0] not in asset_roots:
                continue

            target = vendor.joinpath(*rel_parts)
            if info.is_dir():
                target.mkdir(parents=True, exist_ok=True)
                continue

            target.parent.mkdir(parents=True, exist_ok=True)
            with archive.open(info) as src, target.open('wb') as dst:
                shutil.copyfileobj(src, dst)


def write_file(path: Path, text: str, force: bool) -> bool:
    if path.exists() and not force:
        return False
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(text, encoding='utf-8', newline='\n')
    return True


def main() -> int:
    parser = argparse.ArgumentParser(description='Install a local AdminLTE Blade theme into a Laravel project.')
    parser.add_argument('--project', default='.', help='Laravel project directory')
    parser.add_argument('--source', help='Path to adminlte-laravel-master source folder')
    parser.add_argument('--zip', help='Path to adminlte-laravel-master.zip')
    parser.add_argument('--force', action='store_true', help='Overwrite generated theme files')
    parser.add_argument('--with-demo-route', action='store_true', help='Append a demo route to routes/web.php when possible')
    args = parser.parse_args()

    project = Path(args.project).resolve()
    source = Path(args.source).resolve() if args.source else None
    zip_path = Path(args.zip).resolve() if args.zip else None
    if source is None and zip_path is None:
        default_source = Path(r'D:\1. Selasa 1 Pemrograman Web 3\adminlte-laravel-master')
        default_zip = Path(r'D:\1. Selasa 1 Pemrograman Web 3\adminlte-laravel-master.zip')
        if default_source.exists():
            source = default_source
        elif default_zip.exists():
            zip_path = default_zip
        else:
            raise SystemExit('No source provided. Pass --source or --zip.')
    if source is not None and not source.exists():
        raise SystemExit(f'Source folder not found: {source}')
    if zip_path is not None and not zip_path.exists():
        raise SystemExit(f'ZIP not found: {zip_path}')
    if not (project / 'artisan').exists() and not (project / 'composer.json').exists():
        raise SystemExit(f'{project} does not look like a Laravel project; run inside a Laravel app or pass --project.')

    vendor = project / 'public' / 'vendor' / 'adminlte'
    if source is not None:
        copy_assets_from_folder(source, vendor, args.force)
    else:
        copy_assets_from_zip(zip_path, vendor, args.force)

    written, skipped = [], []
    for rel, content in FILES.items():
        target = project / rel
        (written if write_file(target, content, args.force) else skipped).append(rel)

    if args.with_demo_route:
        route_file = project / 'routes' / 'web.php'
        route = "\nRoute::view('/adminlte-demo', 'adminlte.dashboard')->name('adminlte.demo');\n"
        if route_file.exists():
            current = route_file.read_text(encoding='utf-8')
            if "adminlte.dashboard" not in current:
                route_file.write_text(current.rstrip() + route, encoding='utf-8', newline='\n')
                written.append('routes/web.php')

    print('AdminLTE assets installed to public/vendor/adminlte')
    print('Written: ' + (', '.join(written) if written else '-'))
    if skipped:
        print('Skipped existing files: ' + ', '.join(skipped))
        print('Run again with --force to overwrite generated theme files.')
    return 0


if __name__ == '__main__':
    raise SystemExit(main())
