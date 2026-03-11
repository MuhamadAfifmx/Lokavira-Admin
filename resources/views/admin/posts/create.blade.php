@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root { --teal-primary: #14b8a6; }
    .hero-title span { color: var(--teal-primary); }
    .global-card { background: white; border-radius: 24px; border: 1px solid #e2e8f0; }
    .platform-row { background: #ffffff; border-radius: 20px; border: 1px solid #f1f5f9; position: relative; }
    .input-minimal {
        background: #f8fafc !important; border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important; padding: 10px 14px !important;
        font-size: 0.85rem; transition: all 0.2s;
    }
    .input-minimal:focus { background: white !important; border-color: var(--teal-primary) !important; box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1) !important; }
    .section-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 6px; display: block; }
    .platform-badge { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; }
</style>

<div class="header-content mb-4 d-flex justify-content-between align-items-center" data-aos="fade-down">
    <div>
        <h1 class="hero-title">Laporan <span>Multi-Platform</span></h1>
        <p class="text-muted">Input performa konten mitra: <strong>{{ $user->business_name }}</strong></p>
    </div>
    
    {{-- SWITCHER MANUAL / IMPORT --}}
    <div class="bg-white p-2 rounded-4 shadow-sm d-flex gap-2" style="border: 1px solid #e2e8f0;">
        <button type="button" onclick="switchMethod('manual')" id="btn-manual" class="btn btn-sm fw-bold px-3 py-2 rounded-3 btn-dark">
            <i class="bi bi-pencil-square me-1"></i> Manual
        </button>
        <button type="button" onclick="switchMethod('import')" id="btn-import" class="btn btn-sm fw-bold px-3 py-2 rounded-3 text-muted">
            <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
        </button>
    </div>
</div>

{{-- SECTION IMPORT EXCEL --}}
<div id="section-import" class="d-none" data-aos="zoom-in">
    <div class="global-card p-5 text-center shadow-sm border-0">
        <div class="mx-auto mb-4" style="width: 80px; height: 80px; background: #f0fdf4; color: #22c55e; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-file-earmark-spreadsheet fs-1"></i>
        </div>
        <h4 class="fw-bold">Import Laporan via Excel/CSV</h4>
        <p class="text-muted mx-auto mb-4" style="max-width: 500px;">
            Gunakan file <strong>template_konten.xlsx</strong> (Simpan sebagai CSV). Data akan langsung diupload setelah file dipilih.
        </p>
        
        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="{{ route('admin.posts.download_template', ['user_id' => $user->id]) }}" class="btn btn-outline-success px-4 py-2 fw-bold" style="border-radius: 12px;">
                <i class="bi bi-download me-2"></i> Download Template
            </a>
            
            {{-- Tombol Trigger Upload --}}
            <button type="button" class="btn btn-success px-4 py-2 fw-bold" style="border-radius: 12px;" onclick="document.getElementById('file-excel').click()">
                <i class="bi bi-upload me-2"></i> Pilih File & Upload
            </button>
        </div>

        {{-- Form Hidden untuk Upload --}}
        <form action="{{ route('admin.posts.import_multi') }}" method="POST" enctype="multipart/form-data" id="form-import-excel" class="d-none">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            {{-- Onchange trigger JS di bawah --}}
            <input type="file" id="file-excel" name="excel_file" accept=".csv, .xlsx, .xls">
        </form>
    </div>
</div>

{{-- SECTION MANUAL INPUT --}}
<div id="section-manual">
    @php
        $packageName = strtolower($user->package->name ?? '');
        $platforms = [];
        if (str_contains($packageName, 'all') || str_contains($packageName, 'omni')) {
            $platforms = ['instagram', 'tiktok', 'youtube'];
        } else {
            if (str_contains($packageName, 'tiktok')) $platforms[] = 'tiktok';
            elseif (str_contains($packageName, 'youtube')) $platforms[] = 'youtube';
            else $platforms[] = 'instagram';
        }
    @endphp

    <form action="{{ route('admin.posts.store_multi') }}" method="POST" enctype="multipart/form-data" id="mainForm">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">

        {{-- Section 1: Global Input --}}
        <div class="global-card p-4 mb-4 shadow-sm border-0">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <span class="section-label"><i class="bi bi-image me-1"></i> Cover Konten (File ATAU Embed Link)</span>
                    {{-- Input file dihilangkan 'required'-nya agar bisa diisi Embed saja --}}
                    <input type="file" name="cover_image" class="form-control input-minimal mb-2">
                    <textarea name="cover_embed" class="form-control input-minimal" placeholder="Atau paste link/embed code TikTok di sini..." rows="2"></textarea>
                </div>
                <div class="col-md-3">
                    <span class="section-label"><i class="bi bi-calendar-event me-1"></i> Tanggal Publikasi</span>
                    <input type="date" name="upload_date" class="form-control input-minimal" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-4 text-end">
                    <p class="small text-muted mb-0">Paket Mitra: <span class="badge bg-light text-dark text-uppercase">{{ $packageName }}</span></p>
                </div>
            </div>
        </div>

        {{-- Section 2: Per-Platform Metrics --}}
        <div class="row g-4">
            @foreach($platforms as $p)
            @php
                $color = $p == 'instagram' ? '#E1306C' : ($p == 'tiktok' ? '#000' : '#FF0000');
                $icon = $p == 'instagram' ? 'instagram' : ($p == 'tiktok' ? 'tiktok' : 'youtube');
            @endphp
            <div class="col-12" data-aos="fade-up">
                <div class="platform-row p-4 shadow-sm border-0" style="border-left: 5px solid {{ $color }} !important;">
                    <div class="d-flex align-items-center mb-3">
                        <div class="platform-badge me-2" style="background: {{ $color }};"><i class="bi bi-{{ $icon }}"></i></div>
                        <h6 class="mb-0 fw-bold text-uppercase" style="font-size: 13px; color: {{ $color }};">{{ $p }} Metrics</h6>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <span class="section-label">Link Postingan {{ ucfirst($p) }}</span>
                            <input type="url" name="data[{{ $p }}][post_url]" class="form-control input-minimal" placeholder="https://{{ $p }}.com/..." required>
                        </div>
                        <div class="col-md-2">
                            <span class="section-label">Views</span>
                            <input type="number" name="data[{{ $p }}][views]" class="form-control input-minimal" min="0" value="0" required>
                        </div>
                        <div class="col-md-2">
                            <span class="section-label">Likes</span>
                            <input type="number" name="data[{{ $p }}][likes]" class="form-control input-minimal" min="0" value="0">
                        </div>
                        <div class="col-md-2">
                            <span class="section-label">Comments</span>
                            <input type="number" name="data[{{ $p }}][comments]" class="form-control input-minimal" min="0" value="0">
                        </div>
                        <div class="col-md-2">
                            <span class="section-label">Shares</span>
                            <input type="number" name="data[{{ $p }}][shares]" class="form-control input-minimal" min="0" value="0">
                        </div>

                        @if($p == 'tiktok')
                        <div class="col-md-3">
                            <div class="p-2 px-3 rounded-3" style="background: #eff6ff;">
                                <span class="section-label text-primary" style="font-size: 10px;">Avg. Watch Time</span>
                                <input type="text" name="data[{{ $p }}][avg_watch_time]" class="form-control border-0 bg-transparent p-0 fw-bold" placeholder="12.5s" style="font-size: 13px;">
                            </div>
                        </div>
                        @endif

                        @if($p == 'tiktok' || $p == 'youtube')
                        <div class="col-md-9">
                            <div class="p-2 px-3 rounded-3" style="background: #fff1f2;">
                                <span class="section-label text-danger" style="font-size: 10px;">Audience Age (%)</span>
                                <div class="d-flex gap-2">
                                    <input type="text" name="data[{{ $p }}][age_demographics][18-24]" class="form-control input-minimal py-0 text-center" placeholder="18-24" style="height: 25px;">
                                    <input type="text" name="data[{{ $p }}][age_demographics][25-34]" class="form-control input-minimal py-0 text-center" placeholder="25-34" style="height: 25px;">
                                    <input type="text" name="data[{{ $p }}][age_demographics][35-44]" class="form-control input-minimal py-0 text-center" placeholder="35-44" style="height: 25px;">
                                    <input type="text" name="data[{{ $p }}][age_demographics][45+]" class="form-control input-minimal py-0 text-center" placeholder="45+" style="height: 25px;">
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-5 mb-5 text-center">
            <button type="submit" class="btn btn-dark px-5 py-3 fw-bold shadow-lg" style="border-radius: 15px; background: #0f172a; min-width: 300px;">
                SIMPAN SEMUA LAPORAN
            </button>
            <div class="mt-3">
                <a href="{{ route('admin.posts.index') }}" class="text-muted small text-decoration-none">Batal dan kembali ke dashboard</a>
            </div>
        </div>
    </form>
</div>

<script>
    /**
     * Switcher Logic: Manual vs Import
     */
    function switchMethod(method) {
        const manual = document.getElementById('section-manual');
        const importSection = document.getElementById('section-import');
        const btnManual = document.getElementById('btn-manual');
        const btnImport = document.getElementById('btn-import');

        if(method === 'manual') {
            manual.classList.remove('d-none');
            importSection.classList.add('d-none');
            btnManual.className = 'btn btn-sm fw-bold px-3 py-2 rounded-3 btn-dark';
            btnImport.className = 'btn btn-sm fw-bold px-3 py-2 rounded-3 text-muted';
        } else {
            manual.classList.add('d-none');
            importSection.classList.remove('d-none');
            btnImport.className = 'btn btn-sm fw-bold px-3 py-2 rounded-3 btn-dark';
            btnManual.className = 'btn btn-sm fw-bold px-3 py-2 rounded-3 text-muted';
        }
    }

    /**
     * Auto-Submit & Loading Animation saat file terpilih
     */
    document.getElementById('file-excel').addEventListener('change', function() {
        if(this.value) {
            Swal.fire({
                title: 'Sedang Memproses...',
                text: 'Mohon tunggu sebentar, data sedang dimasukkan ke sistem.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            document.getElementById('form-import-excel').submit();
        }
    });

    /**
     * Notifikasi SweetAlert
     */
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", borderRadius: '20px' });
    @endif
    
    @if(session('error_platform'))
        Swal.fire({ icon: 'error', title: 'Error', text: "{{ session('error_platform') }}", borderRadius: '20px' });
    @endif
</script>
@endsection