@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="header-content mb-5" data-aos="fade-down">
        <h1 class="hero-title text-dark">Dashboard</h1>
        <p class="text-muted font-medium">Monitoring performa <strong>Lokavira</strong> secara real-time.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <a href="{{ route('admin.posts.index') }}" class="stat-item shadow-sm text-decoration-none transition-hover border-0">
                <div class="stat-icon">
                    <i class="bi bi-collection-play"></i>
                </div>
                <div>
                    <small class="text-muted d-block font-bold uppercase tracking-wider" style="font-size: 10px;">Total Postingan</small>
                    <strong class="fs-2 tracking-tight text-dark d-block">{{ number_format($totalPosts) }}</strong>
                </div>
            </a>
        </div>

        <div class="col-md-6">
            <a href="{{ route('admin.users.index') }}" class="stat-item shadow-sm text-decoration-none transition-hover border-0">
                <div class="stat-icon">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div>
                    <small class="text-muted d-block font-bold uppercase tracking-wider" style="font-size: 10px;">Client Aktif</small>
                    <strong class="fs-2 tracking-tight text-dark d-block">{{ $activeUsers }}</strong>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection