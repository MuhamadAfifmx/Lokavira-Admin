<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand d-flex align-items-center gap-2 px-3">
        {{-- LOGO LOKAVIRA --}}
        <img src="{{ asset('logolv.png') }}" height="45" alt="Logo LokaVira">
        <span class="fw-bold fs-5" style="color: var(--teal-primary) !important; letter-spacing: 1px;">LokaVira</span>
    </div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
        </li>

        <li>
            <a href="{{ route('admin.packages.index') }}" class="{{ request()->routeIs('admin.packages.index') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill"></i> Manajemen Paket
            </a>
        </li>

        <li>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Manajemen Client
            </a>
        </li>

        <li>
            <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> Performa Konten
            </a>
        </li>
    </ul>

    {{-- Opsional: Footer sidebar agar lebih profesional --}}
    <div class="sidebar-footer text-center w-100 pb-3" style="position: absolute; bottom: 0; font-size: 10px; color: #ccc;">
        &copy; 2026 LokaVira Admin
    </div>
</aside>

<div id="sidebarOverlay" style="position: fixed; inset: 0; background: rgba(0,0,0,0.3); z-index: 1050; display: none; backdrop-filter: blur(2px);"></div>

<header class="header-admin" id="adminHeader">
    <div class="container-fluid px-4 d-flex justify-content-between align-items-center h-100">
        <button id="sidebarToggle" class="btn p-0 border-0 fs-3 text-teal-primary">
            <i class="bi bi-list"></i>
        </button>

        <div class="dropdown">
            <button class="btn border-0 d-flex align-items-center gap-2 fw-bold" type="button" data-bs-toggle="dropdown">
              <span class="text-dark">{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down text-muted" style="font-size: 10px;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2" style="border-radius: 12px; min-width: 180px;">
                <li>
                    <a class="dropdown-item text-danger fw-bold py-2" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>