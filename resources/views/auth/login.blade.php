@extends('layouts.public')

@section('title', 'Login Admin - ' . config('app.name'))

@section('content')
    <section class="section-login" style="padding: 100px 0; background-color: #f0fdf4; min-height: 80vh; display: flex; align-items: center;">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
                    <div class="card-login" style="background: #fff; padding: 40px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid #dcfce7;">
                        <div class="text-center mb-4">
                            <div class="icon-login" style="width: 70px; height: 70px; background: #16a34a; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                                <i class="fas fa-user-shield fa-2x"></i>
                            </div>
                            <h3 style="font-weight: 800; color: #064e3b; margin-bottom: 10px;">Login Admin</h3>
                            <p class="text-muted small">Kelola input pemeriksaan dan hasil klasifikasi risiko</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger" style="border-radius: 10px; font-size: 0.9rem;">
                                <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.process') }}">
                            @csrf
                            <div class="form-group">
                                <label style="font-weight: 600; color: #064e3b;">Email</label>
                                <input type="email" name="email" class="form-control login-input" placeholder="admin@gmail.com" value="{{ old('email') }}" required autofocus>
                            </div>

                            <div class="form-group">
                                <label style="font-weight: 600; color: #064e3b;">Password</label>
                                <input type="password" name="password" class="form-control login-input" placeholder="••••••••" required>
                            </div>

                            <div class="checkbox mb-4">
                                <label style="color: #4b5563; font-size: 0.9rem; cursor: pointer;">
                                    <input type="checkbox" name="remember"> Ingat saya di perangkat ini
                                </label>
                            </div>

                            <button type="submit" class="btn btn-login btn-block">
                                Masuk
                            </button>
                        </form>
                    </div>
                    
                    <div class="text-center mt-4">
                        <!-- <p class="small text-muted">
                            <i class="fas fa-info-circle"></i> Lupa password? Hubungi tim IT sistem.
                        </p> -->
                        <p class="small text-muted"><i class="fas fa-info-circle"></i> Gunakan akun admin yang tersedia pada database project.</p>
                        <a href="{{ route('home') }}" style="color: #16a34a; font-weight: 600; text-decoration: none;">
                            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Input Styling */
    .login-input {
        height: 45px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }

    .login-input:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        outline: none;
    }

    /* Button Styling */
    .btn-login {
        background-color: #16a34a;
        color: white;
        font-weight: 700;
        padding: 12px;
        border-radius: 10px;
        border: none;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-login:hover {
        background-color: #064e3b;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(6, 78, 59, 0.2);
    }

    /* Animation */
    .card-login {
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Helper spacing */
    .mb-4 { margin-bottom: 20px; }
    .mt-4 { margin-top: 20px; }
</style>
@endpush
