<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/all.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #ffffff;
            color: #1f2937;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main { flex: 1; }
        .site-nav {
            background: #064e3b;
            border: 0;
            border-radius: 0;
            margin-bottom: 0;
            padding: 10px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .site-nav .navbar-brand { font-weight: 800; color: #fff !important; }
        .site-nav .navbar-nav > li > a { color: #dcfce7 !important; font-weight: 600; }
        .site-nav .navbar-nav > .active > a { color: #4ade80 !important; background: transparent !important; }
        .simple-cta {
            background: #f0fdf4;
            border-top: 1px solid #dcfce7;
            padding: 25px 0;
        }
        .footer { background: #052e16; color: #86efac; padding: 20px 0; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-default site-nav">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <i class="fas fa-heartbeat"></i> {{ config('app.name') }}
                </a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Beranda</a></li>
                @auth
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @else
                    <li class="{{ request()->routeIs('login') ? 'active' : '' }}"><a href="{{ route('login') }}">Login</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <section class="simple-cta">
        <div class="container">
            <h5 style="margin: 0; font-weight: 700; color: #064e3b;">
                <i class="fas fa-chart-line"></i> Sistem Informasi Monitoring Jentik Nyamuk
            </h5>
            <p class="text-muted mb-0 small">Mendukung pencatatan lapangan, klasifikasi risiko, dan pemantauan wilayah berbasis data.</p>
        </div>
    </section>

    <footer class="footer">
        <div class="container text-center">
            <p class="mb-0 small"><strong>{{ config('app.name') }}</strong> &copy; {{ date('Y') }}</p>
        </div>
    </footer>

    <script src="{{ asset('vendor/adminlte/js/app.js') }}"></script>
</body>
</html>
