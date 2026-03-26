@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    
    <div class="row mb-5 align-items-center" data-aos="fade-up">
        <div class="col-md-8">
            <h2 class="fw-800 text-dark mb-1">Halo, Admin Lokavira! 👋</h2>
            <p class="text-muted mb-0">Berikut adalah ringkasan performa platform Anda hari ini.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div id="liveClock" class="fw-bold text-teal-primary fs-5"></div>
            <small class="text-muted">{{ date('d F Y') }}</small>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-xl-6 col-md-6">
            <a href="{{ route('admin.posts.index') }}" class="card-stat shadow-sm h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="stat-icon-wrapper me-4 bg-teal-light text-teal-primary">
                        <i class="bi bi-collection-play-fill fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1 tracking-wider">Total Postingan</p>
                        <h2 class="fw-800 text-dark mb-0">{{ number_format($totalPosts) }}</h2>
                        <span class="text-success small fw-bold mt-1 d-block">
                            <i class="bi bi-arrow-up-short"></i> Konten Publik
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-6 col-md-6">
            <a href="{{ route('admin.users.index') }}" class="card-stat shadow-sm h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="stat-icon-wrapper me-4 bg-purple-light text-purple-primary">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1 tracking-wider">Client Aktif</p>
                        <h2 class="fw-800 text-dark mb-0">{{ $activeUsers }}</h2>
                        <span class="text-teal-primary small fw-bold mt-1 d-block">
                            <i class="bi bi-shield-check"></i> Terverifikasi
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

   
</div>

<style>
    :root {
        --teal-primary: #0089a1;
        --teal-light: #e0f2f1;
        --purple-primary: #6f42c1;
        --purple-light: #f3e5f5;
    }

    .fw-800 { font-weight: 800; }
    .tracking-wider { letter-spacing: 1.5px; }

    /* Card Styling */
    .card-stat {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 20px;
        text-decoration: none;
        display: block;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
    }

    .card-stat:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
        border-color: var(--teal-primary);
    }

    .stat-icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .card-stat:hover .stat-icon-wrapper {
        transform: rotate(-10deg) scale(1.1);
    }

    /* Quick Buttons */
    .quick-btn {
        background: white;
        border: 1px solid #eee;
        padding: 12px 24px;
        border-radius: 12px;
        color: #444;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: 0.3s;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }

    .quick-btn:hover {
        background: var(--teal-primary);
        color: white;
        border-color: var(--teal-primary);
        box-shadow: 0 8px 15px rgba(0, 137, 161, 0.2);
    }

    .quick-btn.ghost {
        background: transparent;
        border: 1px dashed #ccc;
    }

    /* Colors Custom */
    .bg-teal-light { background-color: var(--teal-light); }
    .text-teal-primary { color: var(--teal-primary); }
    .bg-purple-light { background-color: var(--purple-light); }
    .text-purple-primary { color: var(--purple-primary); }

    /* Animation */
    [data-aos="fade-up"] {
        animation: fadeInUp 0.8s ease backwards;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    // Live Clock Function
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('liveClock').textContent = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection