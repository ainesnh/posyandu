@extends('layouts.public')

@section('title', 'Beranda - ' . config('app.name'))

@section('content')
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <span class="hero-badge"> Sistem Informasi Monitoring Jentik Nyamuk </span>
                <h1> Posyandu Desa Langenharjo </h1>
                <p> Platform monitoring pemeriksaan jentik nyamuk untuk membantu identifikasi wilayah prioritas berdasarkan klasifikasi risiko otomatis. </p>
            </div>
        </div>
    </section>
    <section class="dashboard-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card low" onclick="showRisk('low')">
                    <i class="fas fa-shield-virus"></i><h3> 12 </h3>
                    <span> Risiko Rendah </span>
                </div>
                <div class="stat-card medium" onclick="showRisk('medium')">
                    <i class="fas fa-exclamation-triangle"></i><h3> 8 </h3>
                    <span> Risiko Sedang </span>
                </div>
                <div class="stat-card high" onclick="showRisk('high')">
                    <i class="fas fa-radiation"></i><h3> 5 </h3>
                    <span> Risiko Tinggi </span>
                </div>
            </div>
            <div class="detail-card">
                <div id="low-risk" class="risk-content">
                    <div class="risk-header low-text"> Risiko Rendah </div>
                    <div class="area-card">
                        <strong>RW 01</strong>
                        <span>RT 01, RT 02, RT 03</span>
                    </div>
                    <div class="area-card">
                        <strong>RW 04</strong>
                        <span>RT 01, RT 02</span>
                    </div>
                </div>
                <div id="medium-risk" class="risk-content" style="display:none">
                    <div class="risk-header medium-text"> Risiko Sedang </div>
                    <div class="area-card">
                        <strong>RW 02</strong>
                        <span>RT 03, RT 05</span>
                    </div>
                </div>
                <div id="high-risk"
                    class="risk-content"
                    style="display:none">
                    <div class="risk-header high-text"> Risiko Tinggi </div>
                    <div class="area-card">
                        <strong>RW 03</strong>
                        <span>RT 01, RT 04, RT 07</span>
                    </div>
                    <div class="area-card">
                        <strong>RW 05</strong>
                        <span>RT 02, RT 03</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function showRisk(type){
            document.querySelectorAll('.risk-content').forEach(x => x.style.display = 'none');
            document.getElementById(type + '-risk').style.display = 'block';
        }
    </script>
@endsection

@push('styles')
<style>
    .hero-section{
        position:relative;
        overflow:hidden;
        padding:120px 0;
        background:
            linear-gradient(135deg,#0f172a,#0f766e);
    }

    .hero-overlay{
        position:absolute;
        inset:0;
        background:
            radial-gradient(circle at top right,
            rgba(255,255,255,.15),
            transparent 30%);
    }

    .hero-content{
        position:relative;
        z-index:2;
        max-width:700px;
        color:#fff;
    }

    .hero-badge{
        display:inline-block;
        padding:8px 14px;
        border-radius:999px;
        background:rgba(255,255,255,.15);
        margin-bottom:20px;
    }

    .hero-content h1{
        font-size:56px;
        font-weight:800;
        line-height:1.1;
        margin-bottom:20px;
    }

    .hero-content p{
        font-size:18px;
        line-height:1.8;
        color:#dbeafe;
    }

    .hero-buttons{
        margin-top:30px;
        display:flex;
        gap:12px;
    }

    .btn-main{
        background:#fff;
        color:#0f766e;
        padding:12px 24px;
        border-radius:10px;
        font-weight:700;
    }

    .btn-outline{
        border:1px solid rgba(255,255,255,.3);
        color:#fff;
        padding:12px 24px;
        border-radius:10px;
    }

    .dashboard-section{
        background:#f8fafc;
        padding:80px 0;
    }

    .stats-grid{
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:20px;
        margin-top:-130px;
        position:relative;
        z-index:10;
    }

    .stat-card{
        background:#fff;
        border-radius:18px;
        padding:30px;
        text-align:center;
        cursor:pointer;
        box-shadow:0 15px 40px rgba(0,0,0,.08);
        transition:.3s;
    }

    .stat-card:hover{
        transform:translateY(-8px);
    }

    .stat-card i{
        font-size:40px;
        margin-bottom:15px;
    }

    .stat-card h3{
        font-size:40px;
        margin:0;
    }

    .stat-card span{
        color:#64748b;
    }

    .low i{color:#22c55e;}
    .medium i{color:#f59e0b;}
    .high i{color:#ef4444;}

    .detail-card{
        background:#fff;
        margin-top:30px;
        border-radius:18px;
        padding:25px;
        box-shadow:0 10px 30px rgba(0,0,0,.06);
    }

    .risk-header{
        font-size:22px;
        font-weight:700;
        margin-bottom:20px;
    }

    .low-text{
        color:#16a34a;
    }

    .medium-text{
        color:#d97706;
    }

    .high-text{
        color:#dc2626;
    }

    .area-card{
        padding:16px;
        border:1px solid #e2e8f0;
        border-radius:12px;
        margin-bottom:12px;
    }

    .area-card strong{
        display:block;
        margin-bottom:5px;
        font-size:16px;
    }

    .area-card span{
        color:#64748b;
    }

    @media(max-width:768px){

        .hero-content h1{
            font-size:38px;
        }

        .stats-grid{
            grid-template-columns:1fr;
            margin-top:-80px;
        }

        .hero-buttons{
            flex-direction:column;
        }
    }
</style>
@endpush
