# AdminLTE Laravel Source Notes

Default local source folder used for this course workspace:
`D:\1. Selasa 1 Pemrograman Web 3\adminlte-laravel-master`

Fallback source ZIP when the folder is unavailable:
`D:\1. Selasa 1 Pemrograman Web 3\adminlte-laravel-master.zip`

The source is the `acacha/adminlte-laravel` package/template. It targets older Laravel/AdminLTE 2 conventions and contains package-style Blade includes such as `adminlte::layouts...`, translations such as `adminlte_lang::message`, and optional helpers like `Gravatar`. Do not copy those Blade files verbatim into modern apps unless the package is actually installed and configured.

Prefer installing a local, project-owned theme:
- Copy static assets from source `public/css`, `public/js`, `public/fonts`, and `public/img` into `public/vendor/adminlte`.
- Use `asset('vendor/adminlte/css/all.css')` and `asset('vendor/adminlte/js/app.js')`, not `mix()`, unless the project already has the matching Mix manifest.
- Create local Blade files under `resources/views/layouts/adminlte...` so the app is not coupled to the package namespace.
- Keep auth UI defensive: use `@auth`, `@guest`, `Route::has(...)`, and `auth()->user()` checks. Avoid direct `$user`, `Gravatar`, or package translation calls unless the project already provides them.

After installation, pages can use:

```blade
@extends('layouts.adminlte')

@section('title', 'Dashboard')

@section('content')
    <div class="box">
        <div class="box-header with-border"><h3 class="box-title">Judul</h3></div>
        <div class="box-body">Konten halaman.</div>
    </div>
@endsection
```
