@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="header-content mb-4" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="hero-title" style="font-size: 1.8rem; font-weight: 800;">Content <span style="color: var(--teal-primary);">Intelligence</span></h1>
            <p class="text-muted small fw-bold mb-0">Monitor Real-time Performance & Engagement</p>
        </div>
        
        <div class="d-flex gap-2 flex-wrap">
            <div class="dropdown">
                <button class="btn btn-white shadow-sm fw-bold dropdown-toggle" type="button" data-bs-toggle="dropdown" style="border-radius: 10px; border: 1px solid #eee;">
                    <i class="bi bi-people-fill me-2 text-teal-primary"></i> <span id="selected-mitra-label">Semua Mitra</span>
                </button>
                <ul class="dropdown-menu border-0 shadow-lg" style="border-radius: 12px;">
                    <li><a class="dropdown-item fw-bold" href="javascript:void(0)" onclick="filterByMitra('all')">Semua Mitra</a></li>
                    <li><hr class="dropdown-divider"></li>
                    @foreach($users as $user)
                    <li><a class="dropdown-item fw-medium" href="javascript:void(0)" onclick="filterByMitra('{{ $user->business_name }}')">{{ $user->business_name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="dropdown">
                <button class="btn btn-white shadow-sm fw-bold dropdown-toggle" type="button" data-bs-toggle="dropdown" style="border-radius: 10px; border: 1px solid #eee;">
                    <i class="bi bi-layers-fill me-2 text-teal-primary"></i> <span id="selected-platform-label">Semua Platform</span>
                </button>
                <ul class="dropdown-menu border-0 shadow-lg" style="border-radius: 12px;">
                    <li><a class="dropdown-item fw-bold" href="javascript:void(0)" onclick="filterByPlatform('all')">Semua Platform</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="filterByPlatform('tiktok')">TikTok</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="filterByPlatform('instagram')">Instagram</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="filterByPlatform('youtube')">YouTube</a></li>
                </ul>
            </div>

            <div class="dropdown">
                <button class="btn btn-white shadow-sm fw-bold dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="border-radius: 10px; border: 1px solid #eee;">
                    <i class="bi bi-calendar-check me-2 text-teal-primary"></i> <span id="selected-range-label">7 Hari Terakhir</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-3" style="border-radius: 12px; min-width: 280px;">
                    <li><button class="dropdown-item fw-bold rounded-2 mb-1" onclick="setQuickRange(7)">7 Hari Terakhir</button></li>
                    <li><button class="dropdown-item fw-bold rounded-2 mb-1" onclick="setQuickRange(30)">30 Hari Terakhir</button></li>
                    <li><hr class="dropdown-divider"></li>
                    <li class="px-3 py-2">
                        <label class="small fw-bold text-muted mb-2">CUSTOM RANGE</label>
                        <div class="d-flex flex-column gap-2">
                            <input type="date" id="startDate" onchange="applyCustomDate()" class="form-control form-control-sm border-light shadow-none fw-bold" style="background: #f8fafc;">
                            <input type="date" id="endDate" onchange="applyCustomDate()" class="form-control form-control-sm border-light shadow-none fw-bold" style="background: #f8fafc;">
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="admin-card mb-4 border-0 shadow-sm p-4" style="background: #ffffff; border-radius: 20px;">
    <div class="row g-4">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-800 text-dark mb-0">Tren Visualisasi <small class="text-muted fw-normal fs-6 ms-2">(Klik titik untuk detail konten)</small></h5>
                <div id="chartLegend" class="d-flex gap-3 flex-wrap justify-content-end"></div>
            </div>
            <div style="height: 400px; position: relative;">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
        
        <div class="col-md-2 border-start ps-4">
            <label class="small fw-800 text-muted mb-3 d-block text-uppercase" style="letter-spacing: 1px; font-size: 10px;">Metrik</label>
            <div class="d-flex flex-column gap-2">
                <div onclick="updateChartMetric('all')" id="tab-all" class="metric-card active-metric" title="Total: Views + Likes + Comments + Shares">
                    <i class="bi bi-grid-1x2"></i> <small>All Stats</small>
                </div>
                <div onclick="updateChartMetric('views')" id="tab-views" class="metric-card">
                    <i class="bi bi-eye"></i> <small>Views</small>
                </div>
                <div onclick="updateChartMetric('likes')" id="tab-likes" class="metric-card">
                    <i class="bi bi-heart"></i> <small>Likes</small>
                </div>
                <div onclick="updateChartMetric('comments')" id="tab-comments" class="metric-card">
                    <i class="bi bi-chat-dots"></i> <small>Comments</small>
                </div>
                <div onclick="updateChartMetric('shares')" id="tab-shares" class="metric-card">
                    <i class="bi bi-share"></i> <small>Shares</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="admin-card border-0 shadow-sm overflow-hidden" style="background: #ffffff; border-radius: 20px;">
    <div class="p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
        <h5 class="fw-800 text-dark mb-0">Riwayat Performa Harian</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="p-3 ps-4 border-0 text-muted small fw-bold">KONTEN</th>
                    <th class="p-3 border-0 text-muted small fw-bold">PLATFORM</th>
                    <th class="p-3 border-0 text-muted small fw-bold">ENGAGEMENT</th>
                    <th class="p-3 border-0 text-muted small fw-bold text-center">TGL UPLOAD</th>
                    <th class="p-3 border-0 text-muted small fw-bold text-center pe-4">AKSI</th>
                </tr>
            </thead>
            <tbody id="postTableBody">
                @foreach($posts as $post)
                <tr class="post-row" data-user="{{ $post->user->business_name }}" data-date="{{ $post->upload_date->format('Y-m-d') }}" data-platform="{{ strtolower($post->platform) }}">
                    <td class="p-3 ps-4 border-0">
                        <div class="d-flex align-items-center">
                            <div class="me-3 position-relative shadow-sm" style="width: 80px; height: 110px; border-radius: 12px; overflow: hidden; cursor: pointer; background: #000; flex-shrink: 0;" onclick="showDetailFromTable({{ json_encode($post) }}, '{{ $post->user->business_name }}')">
                                <div style="position: absolute; inset: 0; z-index: 10;"></div>
                                
                                @php
                                    $url = $post->post_url;
                                    $thumbnail = null;

                                    if(str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
                                        preg_match('/(?:shorts\/|v=|be\/)([\w-]{11})/', $url, $matches);
                                        $id = $matches[1] ?? '';
                                        $thumbnail = "https://img.youtube.com/vi/$id/mqdefault.jpg";
                                    } elseif(str_contains($url, 'instagram.com')) {
                                        // Solusi Paksa: Menggunakan API gratis thum.io atau screenshot service agar IG muncul
                                        $thumbnail = "https://images.weserv.nl/?url=" . urlencode(rtrim($url, '/') . '/media/?size=m');
                                    }
                                @endphp

                                @if($thumbnail)
                                    <img src="{{ $thumbnail }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name=?'">
                                @elseif(str_contains($post->cover_image, '<blockquote') || str_contains($post->cover_image, '<iframe'))
                                    <div style="width: 320px; height: 440px; transform: scale(0.25); transform-origin: top left; pointer-events: none;">
                                        {!! $post->cover_image !!}
                                    </div>
                                @elseif(filter_var($post->cover_image, FILTER_VALIDATE_URL))
                                    <img src="{{ $post->cover_image }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <img src="{{ asset('storage/'.$post->cover_image) }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name=NA'">
                                @endif
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $post->user->business_name }}</div>
                                <small class="text-muted" style="font-size: 11px;">#{{ $post->id }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="p-3 border-0">
                        <span class="badge" style="background: #f1f5f9; color: #475569; border-radius: 6px; padding: 6px 10px; font-weight: 700; border: 1px solid #e2e8f0;">{{ strtoupper($post->platform) }}</span>
                    </td>
                    <td class="p-3 border-0">
                        <div class="fw-bold text-dark">{{ number_format($post->views) }} <small class="text-muted fw-normal">Views</small></div>
                    </td>
                    <td class="p-3 border-0 text-center text-muted small fw-bold">
                        {{ $post->upload_date->format('d/m/Y') }}
                    </td>
                    <td class="p-3 border-0 text-center pe-4">
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm text-primary bg-light border-0" onclick="showDetailFromTable({{ json_encode($post) }}, '{{ $post->user->business_name }}')">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                            <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm text-warning bg-light border-0"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm text-danger bg-light border-0" onclick="return confirm('Hapus data ini?')"><i class="bi bi-trash3-fill"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
            <div class="modal-body p-0 bg-white">
                <div class="row g-0">
                    <div id="media-container" class="col-md-5 d-flex align-items-center justify-content-center bg-light p-3" style="min-height: 600px; max-height: 850px; overflow-y: auto;">
                    </div>
                    <div class="col-md-7 p-4 bg-white d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="pe-3">
                                <h4 id="detail-mitra" class="fw-900 text-dark mb-1" style="font-size: 1.4rem;"></h4>
                                <div class="d-flex align-items-center gap-2">
                                    <span id="detail-platform-badge" class="badge text-uppercase" style="border-radius: 6px; padding: 6px 12px; font-weight: 700; background: var(--teal-primary); color: white;"></span>
                                    <div class="text-muted small fw-bold"><i class="bi bi-calendar3 me-1"></i> <span id="detail-date"></span></div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="row g-3 mb-4" id="detail-stats-grid"></div>
                        <div id="detail-extra-container" class="p-3 rounded-4 border bg-white mb-4 flex-grow-1">
                            <div id="detail-watch-time" class="mb-3 d-none border-bottom pb-2">
                                <small class="text-muted fw-bold d-block mb-1" style="font-size: 10px; letter-spacing: 1px;">RATA-RATA LAMA MENONTON</small>
                                <span class="fw-800 text-dark fs-5" id="val-watch-time"></span>
                            </div>
                            <div class="demographics-section">
                                <small class="text-muted fw-bold d-block mb-2" style="font-size: 10px; letter-spacing: 1px;">DEMOGRAFI USIA</small>
                                <div id="detail-age-list" class="d-flex flex-wrap gap-2"></div>
                            </div>
                        </div>
                        <a id="detail-link" href="#" target="_blank" class="btn btn-dark w-100 fw-bold py-3 mt-auto shadow-sm" style="border-radius: 12px; transition: all 0.3s;">
                            <i class="bi bi-box-arrow-up-right me-2"></i> Kunjungi Postingan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root { --teal-primary: #0089a1; }
    .btn-white { background: white; border: 1px solid #eee; }
    .metric-card {
        padding: 10px; background: #f8fafc; border-radius: 12px; cursor: pointer;
        display: flex; align-items: center; gap: 8px; transition: all 0.2s;
        border: 2px solid transparent; font-weight: 700; color: #64748b;
    }
    .metric-card:hover { background: #fff; border-color: #eee; }
    .active-metric { background: #fff !important; border-color: var(--teal-primary) !important; color: var(--teal-primary) !important; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .legend-item { font-size: 11px; font-weight: 700; color: #64748b; display: flex; align-items: center; gap: 6px; }
    .legend-dot { width: 8px; height: 8px; border-radius: 50%; }
    canvas { cursor: crosshair; }
    .ratio-9x16 { --bs-aspect-ratio: 177.77%; }
    .video-portrait-container {
        width: 100%; max-width: 310px; margin: 0 auto; border-radius: 20px;
        overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        background: #000; border: 4px solid #fff;
    }
    #media-container::-webkit-scrollbar { width: 4px; }
    #media-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .post-row blockquote, .post-row iframe { margin: 0 !important; max-width: none !important; }
</style>

<script async src="//www.instagram.com/embed.js"></script>

<script>
    let myChart;
    const colors = ['#0089a1', '#f59e0b', '#e11d48', '#4f46e5', '#7c3aed', '#059669'];
    const platformColors = { 'tiktok': '#000000', 'instagram': '#833AB4', 'youtube': '#FF0000' };
    let currentMitra = 'all', currentPlatform = 'all', currentMetric = 'all';

    const allData = {!! json_encode($users->map(fn($u) => [
        'name' => $u->business_name,
        'posts' => $u->posts->map(fn($p) => [
            'id' => $p->id,
            'views' => (int)$p->views,
            'likes' => (int)$p->likes,
            'comments' => (int)$p->comments,
            'shares' => (int)$p->shares,
            'all_sum' => (int)$p->views + (int)$p->likes + (int)$p->comments + (int)$p->shares,
            'upload_date' => $p->upload_date->format('Y-m-d'),
            'date_raw' => \Carbon\Carbon::parse($p->upload_date)->format('Y-m-d'),
            'date' => \Carbon\Carbon::parse($p->upload_date)->format('d/m'),
            'platform' => strtolower($p->platform),
            'cover_image' => $p->cover_image,
            'post_url' => $p->post_url,
            'avg_watch_time' => $p->avg_watch_time,
            'age_demographics' => $p->age_demographics
        ])->sortBy('date_raw')->values()->toArray()
    ])->toArray()) !!};

    function initChart() {
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const legendContainer = document.getElementById('chartLegend');
        legendContainer.innerHTML = '';
        const start = document.getElementById('startDate').value;
        const end = document.getElementById('endDate').value;

        const dataToDisplay = (currentMitra === 'all' ? allData : allData.filter(m => m.name === currentMitra))
            .map(mitra => ({
                ...mitra,
                posts: mitra.posts.filter(p => {
                    const matchPlatform = (currentPlatform === 'all' || p.platform === currentPlatform);
                    const matchDate = (!start || p.date_raw >= start) && (!end || p.date_raw <= end);
                    return matchPlatform && matchDate;
                })
            })).filter(m => m.posts.length > 0);

        const labels = [...new Set(dataToDisplay.flatMap(m => m.posts.map(p => p.date)))].sort();
        const datasets = [];

        if (currentMitra !== 'all' && currentPlatform === 'all') {
            ['tiktok', 'instagram', 'youtube'].forEach(plt => {
                const pltPosts = dataToDisplay[0].posts.filter(p => p.platform === plt);
                if (pltPosts.length > 0) {
                    datasets.push({
                        label: plt.toUpperCase(),
                        data: labels.map(l => { 
                            const p = pltPosts.find(post => post.date === l); 
                            if (!p) return 0;
                            return currentMetric === 'all' ? p.all_sum : p[currentMetric]; 
                        }),
                        borderColor: platformColors[plt], 
                        backgroundColor: platformColors[plt] + '15', 
                        fill: true, tension: 0.4, pointRadius: 5, pointHoverRadius: 8
                    });
                    addLegendItem(plt.toUpperCase(), platformColors[plt]);
                }
            });
        } else {
            dataToDisplay.forEach((mitra, index) => {
                const color = colors[index % colors.length];
                datasets.push({
                    label: mitra.name,
                    data: labels.map(l => { 
                        const p = mitra.posts.find(post => post.date === l); 
                        if (!p) return 0;
                        return currentMetric === 'all' ? p.all_sum : p[currentMetric]; 
                    }),
                    borderColor: color, backgroundColor: color + '10', fill: true, tension: 0.4, pointRadius: 5, pointHoverRadius: 8
                });
                addLegendItem(mitra.name, color);
            });
        }

        if (myChart) myChart.destroy();
        myChart = new Chart(ctx, { 
            type: 'line', data: { labels, datasets }, 
            options: { 
                responsive: true, maintainAspectRatio: false, spanGaps: true, 
                onClick: (e, activeEls) => {
                    if (activeEls.length > 0) {
                        const dataIndex = activeEls[0].index;
                        const datasetIndex = activeEls[0].datasetIndex;
                        findAndShowDetail(labels[dataIndex], datasets[datasetIndex].label);
                    }
                },
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => `${context.dataset.label}: ${context.raw.toLocaleString()}`,
                            footer: () => 'Klik titik untuk detail konten'
                        }
                    }
                }, 
                scales: { 
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, 
                    x: { grid: { display: false } } 
                } 
            } 
        });
    }

    function findAndShowDetail(dateLabel, identifier) {
        allData.forEach(mitra => {
            mitra.posts.forEach(post => {
                if (post.date === dateLabel) {
                    if (mitra.name === identifier || post.platform.toUpperCase() === identifier) {
                        showDetailFromTable(post, mitra.name);
                    }
                }
            });
        });
    }

    function addLegendItem(name, color) {
        const leg = document.createElement('div');
        leg.className = 'legend-item';
        leg.innerHTML = `<span class="legend-dot" style="background:${color}"></span> ${name}`;
        document.getElementById('chartLegend').appendChild(leg);
    }

    function filterByMitra(name) { currentMitra = name; document.getElementById('selected-mitra-label').innerText = name === 'all' ? 'Semua Mitra' : name; initChart(); applyFilters(); }
    function filterByPlatform(plt) { currentPlatform = plt; const lbl = { all: 'Semua Platform', tiktok: 'TikTok', instagram: 'Instagram', youtube: 'YouTube' }; document.getElementById('selected-platform-label').innerText = lbl[plt] || 'Semua Platform'; initChart(); applyFilters(); }
    function updateChartMetric(m) { currentMetric = m; document.querySelectorAll('.metric-card').forEach(c => c.classList.remove('active-metric')); document.getElementById('tab-' + m).classList.add('active-metric'); initChart(); }

    function setQuickRange(days) {
        const end = new Date(), start = new Date(); start.setDate(end.getDate() - days);
        document.getElementById('endDate').value = end.toISOString().split('T')[0];
        document.getElementById('startDate').value = start.toISOString().split('T')[0];
        document.getElementById('selected-range-label').innerText = days + ' Hari Terakhir';
        initChart(); applyFilters();
    }

    function applyCustomDate() { if (document.getElementById('startDate').value && document.getElementById('endDate').value) { document.getElementById('selected-range-label').innerText = 'Custom Range'; initChart(); applyFilters(); } }

    function applyFilters() {
        const s = document.getElementById('startDate').value, e = document.getElementById('endDate').value;
        document.querySelectorAll('.post-row').forEach(row => {
            const matchUser = currentMitra === 'all' || row.dataset.user === currentMitra;
            const matchPlt = currentPlatform === 'all' || row.dataset.platform === currentPlatform;
            const matchDate = (!s || row.dataset.date >= s) && (!e || row.dataset.date <= e);
            row.style.display = (matchUser && matchPlt && matchDate) ? '' : 'none';
        });
    }

    function showDetailFromTable(p, name) {
        const grid = document.getElementById('detail-stats-grid');
        grid.innerHTML = `
            <div class="col-6"><div class="p-3 rounded-4 border bg-light text-center h-100"><small class="text-muted fw-bold d-block mb-1" style="font-size: 10px;">VIEWS</small><h4 class="fw-800 mb-0">${p.views.toLocaleString()}</h4></div></div>
            <div class="col-6"><div class="p-3 rounded-4 border bg-light text-center h-100"><small class="text-muted fw-bold d-block mb-1" style="font-size: 10px;">LIKES</small><h4 class="fw-800 mb-0">${p.likes.toLocaleString()}</h4></div></div>
            <div class="col-6"><div class="p-3 rounded-4 border bg-light text-center h-100"><small class="text-muted fw-bold d-block mb-1" style="font-size: 10px;">COMMENTS</small><h4 class="fw-800 mb-0">${p.comments.toLocaleString()}</h4></div></div>
            <div class="col-6"><div class="p-3 rounded-4 border bg-light text-center h-100"><small class="text-muted fw-bold d-block mb-1" style="font-size: 10px;">SHARES</small><h4 class="fw-800 mb-0">${p.shares.toLocaleString()}</h4></div></div>
        `;
        const mediaContainer = document.getElementById('media-container');
        const modalEl = document.getElementById('modalDetail');
        let finalMedia = '';
        let url = p.post_url;
        if (p.cover_image && (p.cover_image.includes('<blockquote') || p.cover_image.includes('<iframe'))) {
            finalMedia = `<div class="w-100 px-3"><div class="video-portrait-container"><div class="ratio ratio-9x16">${p.cover_image}</div></div></div>`;
        } else {
            if (url.includes('youtube.com/shorts/')) {
                let id = url.split('shorts/')[1].split('?')[0];
                finalMedia = renderPortraitIframe(`https://www.youtube.com/embed/${id}`);
            } else if (url.includes('tiktok.com')) {
                let id = url.match(/video\/(\d+)/) ? url.match(/video\/(\d+)/)[1] : null;
                if (!id && p.cover_image) id = p.cover_image.match(/data-video-id=["']?(\d+)["']?/) ? p.cover_image.match(/data-video-id=["']?(\d+)["']?/)[1] : null;
                if (id) finalMedia = renderPortraitIframe(`https://www.tiktok.com/embed/v2/${id}`);
                else finalMedia = renderImage(p.cover_image);
            } else if (url.includes('instagram.com')) {
                finalMedia = `<div class="w-100 px-2"><div class="video-portrait-container" style="background: white; overflow-y: auto; max-height: 600px; border:none;"><blockquote class="instagram-media" data-instgrm-permalink="${url}" data-instgrm-version="14" style="width:100%; margin:0; border:none; box-shadow:none;"></blockquote></div></div>`;
            } else {
                finalMedia = renderImage(p.cover_image);
            }
        }
        mediaContainer.innerHTML = finalMedia;
        document.getElementById('detail-mitra').innerText = name;
        document.getElementById('detail-platform-badge').innerText = p.platform.toUpperCase();
        document.getElementById('detail-date').innerText = new Date(p.upload_date).toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' });
        document.getElementById('detail-link').href = p.post_url;
        const watchContainer = document.getElementById('detail-watch-time');
        if (p.platform.toLowerCase() === 'tiktok' && p.avg_watch_time) {
            watchContainer.classList.remove('d-none');
            document.getElementById('val-watch-time').innerText = p.avg_watch_time;
        } else { watchContainer.classList.add('d-none'); }
        const ageList = document.getElementById('detail-age-list');
        ageList.innerHTML = '';
        if (p.age_demographics) {
            Object.entries(p.age_demographics).forEach(([age, val]) => {
                const span = document.createElement('span');
                span.className = 'badge bg-light text-dark border p-2 fw-bold';
                span.style.borderRadius = "8px";
                span.innerHTML = `<span class="text-muted small">${age}:</span> ${val}`;
                ageList.appendChild(span);
            });
        }
        let modalObj = new bootstrap.Modal(modalEl);
        modalObj.show();
        if (url.includes('instagram.com') && typeof instgrm !== 'undefined') {
            setTimeout(() => { instgrm.Embeds.process(); }, 300);
        }
    }

    function renderPortraitIframe(src) {
        return `<div class="w-100 px-3"><div class="video-portrait-container"><div class="ratio ratio-9x16"><iframe src="${src}" allowfullscreen style="border:none;"></iframe></div></div></div>`;
    }

    function renderImage(img) {
        let path = img.startsWith('http') ? img : window.location.origin + '/storage/' + img;
        return `<div class="px-3"><img src="${path}" class="shadow-sm rounded-4 w-100" style="max-height: 580px; object-fit: contain; border: 4px solid #fff;"></div>`;
    }

    document.addEventListener('DOMContentLoaded', () => {
        setQuickRange(7);
        if (typeof instgrm !== 'undefined') { instgrm.Embeds.process(); }
    });
</script>
@endsection