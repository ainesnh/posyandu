---
name: laravel-adminlte-theme
description: "Install or adapt an AdminLTE 2 theme for Laravel apps from the local adminlte-laravel-master folder or ZIP source. Use for Laravel theme, AdminLTE, Blade layout, asset path, auth menu, sidebar, and dashboard integration tasks."
---

# Laravel AdminLTE Theme

## Workflow

1. Inspect the target folder first. Confirm it is a Laravel app by checking for `artisan`, `composer.json`, `resources/views`, `public`, and `routes/web.php`.
2. If the folder is not a Laravel app, do not scatter theme files into it. Explain that a Laravel project is needed, or create only a clearly named starter/theme folder if the user explicitly wants that.
3. Locate the source folder. Default to `D:\1. Selasa 1 Pemrograman Web 3\adminlte-laravel-master` when it exists. If the folder is unavailable, use `D:\1. Selasa 1 Pemrograman Web 3\adminlte-laravel-master.zip` when it exists; otherwise ask for the source path.
4. Read `references/adminlte_source_notes.md` before adapting Blade files from this source.
5. Prefer the bundled installer for a normal install:

```powershell
python "C:\Users\user\.codex\skills\laravel-adminlte-theme\scripts\install_adminlte_theme.py" --project "<laravel-project>" --source "D:\1. Selasa 1 Pemrograman Web 3\adminlte-laravel-master" --with-demo-route
```

Use `--force` only when the user approves overwriting generated theme files.

## Integration Rules

- Keep assets under `public/vendor/adminlte` to avoid overwriting existing app assets.
- Use `asset('vendor/adminlte/...')` in Blade unless the project already builds the same assets through Vite/Mix.
- Create local Blade layouts such as `resources/views/layouts/adminlte.blade.php`; do not rely on `adminlte::` namespaces unless the Composer package is installed.
- Replace package-only helpers and translations with project-safe Laravel constructs: `@auth`, `@guest`, `Route::has`, `auth()->user()`, and plain Indonesian labels when appropriate.
- Preserve existing app routes, controllers, models, migrations, and user edits. Add demo routes only when requested or useful for verification.
- For a warung/restaurant assignment, make sidebar labels practical: Dashboard, Produk, Pesanan, Pelanggan, Laporan.

## Verification

After installing, run the lightest available checks:

```powershell
php artisan view:clear
php artisan route:list
```

If PHP or dependencies are unavailable, verify by checking that generated Blade files exist and the referenced asset files exist under `public/vendor/adminlte`.
