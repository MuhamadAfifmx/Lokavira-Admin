@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-800 text-dark mb-1">Manajemen Paket</h4>
            <p class="text-muted small mb-0">Kelola daftar layanan digital marketing Lokavira</p>
        </div>
        <button type="button" class="btn btn-primary px-4 py-2 shadow-sm border-0 d-flex align-items-center" 
                style="border-radius: 10px; background: var(--teal-primary); transition: 0.3s;" 
                data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-2"></i> Tambah Paket
        </button>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <div class="col-lg-8">
                    <div class="nav nav-pills gap-2" id="filterPlatform">
                        <button class="nav-link active btn-filter-pill" data-filter="all">Semua</button>
                        <button class="nav-link btn-filter-pill" data-filter="Instagram"><i class="bi bi-instagram me-1"></i> Instagram</button>
                        <button class="nav-link btn-filter-pill" data-filter="TikTok"><i class="bi bi-tiktok me-1"></i> TikTok</button>
                        <button class="nav-link btn-filter-pill" data-filter="YouTube"><i class="bi bi-youtube me-1"></i> YouTube</button>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex align-items-center justify-content-lg-end">
                        <i class="bi bi-sort-down me-2 text-muted"></i>
                        <select class="form-select border-0 bg-light fw-bold w-auto" id="sortHarga" style="border-radius: 8px; cursor: pointer; font-size: 14px;">
                            <option value="default">Urutkan Harga</option>
                            <option value="termurah">Termurah</option>
                            <option value="termahal">Termahal</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablePaket">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th class="px-4 py-3 border-0 text-muted small fw-bold text-uppercase">Layanan</th>
                        <th class="px-4 py-3 border-0 text-muted small fw-bold text-uppercase text-center">Detail Fitur</th>
                        <th class="px-4 py-3 border-0 text-muted small fw-bold text-uppercase">Harga</th>
                        <th class="px-4 py-3 border-0 text-muted small fw-bold text-uppercase text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody id="packageBody">
                    @foreach($packages as $pkg)
                    <tr class="package-row" data-platform="{{ $pkg->type }}" data-price="{{ $pkg->price }}">
                        <td class="px-4 py-3 border-0">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 42px; height: 42px; background: #e0f2f1; border-radius: 10px; color: var(--teal-primary);">
                                    @if($pkg->type == 'Instagram') <i class="bi bi-instagram fs-5"></i>
                                    @elseif($pkg->type == 'TikTok') <i class="bi bi-tiktok fs-5"></i>
                                    @else <i class="bi bi-youtube fs-5"></i> @endif
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $pkg->name }}</h6>
                                    <span class="text-muted" style="font-size: 11px;">{{ $pkg->type }} Service</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 border-0 text-center">
                            <button class="btn btn-sm px-3 rounded-pill fw-bold border-0" 
                                    style="background: #f1f3f5; color: #495057; font-size: 11px;"
                                    onclick="showFeatures('{{ $pkg->name }}', {{ $pkg->features }})">
                                {{ count($pkg->features) }} Item <i class="bi bi-eye ms-1"></i>
                            </button>
                        </td>
                        <td class="px-4 py-3 border-0">
                            <span class="fw-bold text-dark" style="font-size: 15px;">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-4 py-3 border-0 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-light btn-sm rounded-3 px-3 shadow-sm border" onclick="editPaket({{ $pkg }}, {{ $pkg->features }})">
                                    <i class="bi bi-pencil-fill text-warning"></i>
                                </button>
                                <form action="{{ route('admin.packages.destroy', $pkg->id) }}" method="POST" class="form-hapus">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-light btn-sm rounded-3 px-3 shadow-sm border btn-delete">
                                        <i class="bi bi-trash3-fill text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFitur" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold text-dark mb-0" id="fiturTitle">Detail Fitur</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="featureList" class="d-flex flex-column gap-2"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header p-4 border-0">
                <h5 class="fw-bold text-dark mb-0">Buat Paket Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.packages.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2">NAMA PAKET</label>
                            <input type="text" name="name" class="form-control bg-light border-0 p-3 rounded-3" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2">PLATFORM</label>
                            <select name="type" class="form-select bg-light border-0 p-3 rounded-3">
                                <option value="Instagram">Instagram</option>
                                <option value="TikTok">TikTok</option>
                                <option value="YouTube">YouTube</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2">HARGA (Rp)</label>
                            <input type="number" name="price" class="form-control bg-light border-0 p-3 rounded-3" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="small fw-bold text-muted">DAFTAR FITUR</label>
                            <button type="button" onclick="tambahBaris('list-fitur-tambah')" class="btn btn-sm text-teal-primary fw-bold p-0">
                                <i class="bi bi-plus-circle-fill"></i> Tambah Baris
                            </button>
                        </div>
                        <div id="list-fitur-tambah" class="row g-2">
                            <div class="col-md-4 item-fitur">
                                <input type="text" name="features[]" class="form-control bg-light border-0 p-2 small" placeholder="Contoh: 15 Postingan" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 p-3 fw-bold shadow-sm" style="background: var(--teal-primary); border:none; border-radius: 10px;">SIMPAN PAKET</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header p-4 border-0">
                <h5 class="fw-bold text-dark mb-0">Edit Informasi Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditPaket" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2 text-uppercase">Nama Paket</label>
                            <input type="text" name="name" id="edit_name" class="form-control bg-light border-0 p-3 rounded-3" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2 text-uppercase">Platform</label>
                            <select name="type" id="edit_type" class="form-select bg-light border-0 p-3 rounded-3">
                                <option value="Instagram">Instagram</option>
                                <option value="TikTok">TikTok</option>
                                <option value="YouTube">YouTube</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2 text-uppercase">Harga (Rp)</label>
                            <input type="number" name="price" id="edit_price" class="form-control bg-light border-0 p-3 rounded-3" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="small fw-bold text-muted text-uppercase">Edit Fitur</label>
                            <button type="button" onclick="tambahBaris('list-fitur-edit')" class="btn btn-sm text-teal-primary fw-bold p-0">
                                <i class="bi bi-plus-circle-fill"></i> Tambah Baris
                            </button>
                        </div>
                        <div id="list-fitur-edit" class="row g-2"></div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 p-3 fw-bold shadow-sm" style="background: var(--teal-primary); border:none; border-radius: 10px;">PERBARUI DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    :root { --teal-primary: #0089a1; }
    .fw-800 { font-weight: 800; }
    .btn-filter-pill { 
        border-radius: 10px !important; 
        color: #6c757d; 
        font-weight: 600; 
        font-size: 14px;
        transition: 0.3s;
        border: 1px solid transparent;
    }
    .btn-filter-pill:hover { background: #f1f3f5; color: var(--teal-primary); }
    .nav-pills .nav-link.active { background-color: var(--teal-primary) !important; color: white !important; box-shadow: 0 4px 10px rgba(0, 137, 161, 0.2); }
    .icon-box { transition: 0.3s; }
    tr:hover .icon-box { transform: scale(1.1); }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. FILTER PLATFORM
    document.querySelectorAll('.btn-filter-pill').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-filter-pill').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            const rows = document.querySelectorAll('.package-row');
            
            rows.forEach(row => {
                if (filter === 'all' || row.getAttribute('data-platform') === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // 2. SORTING HARGA
    document.getElementById('sortHarga').addEventListener('change', function() {
        const tbody = document.getElementById('packageBody');
        const rows = Array.from(tbody.querySelectorAll('.package-row'));
        const sortType = this.value;

        if (sortType === 'default') return;

        rows.sort((a, b) => {
            const priceA = parseInt(a.getAttribute('data-price'));
            const priceB = parseInt(b.getAttribute('data-price'));
            return sortType === 'termurah' ? priceA - priceB : priceB - priceA;
        });

        rows.forEach(row => tbody.appendChild(row));
    });

    // 3. DETAIL FITUR (MODAL)
    function showFeatures(packageName, features) {
        document.getElementById('fiturTitle').innerText = packageName;
        const container = document.getElementById('featureList');
        container.innerHTML = '';
        
        features.forEach(f => {
            const item = document.createElement('div');
            item.className = 'd-flex align-items-center small py-1 text-dark fw-semibold';
            item.innerHTML = `<i class="bi bi-check2-circle text-teal-primary me-2"></i> ${f.feature_name}`;
            container.appendChild(item);
        });
        
        new bootstrap.Modal(document.getElementById('modalFitur')).show();
    }

    // 4. DINAMIS TAMBAH/HAPUS BARIS INPUT
    function tambahBaris(containerId, value = '') {
        const wrapper = document.getElementById(containerId);
        const col = document.createElement('div');
        col.className = 'col-md-4 position-relative item-fitur';
        col.innerHTML = `
            <div class="input-group">
                <input type="text" name="features[]" value="${value}" class="form-control bg-light border-0 p-2 small" style="border-radius: 8px;" placeholder="Isi fitur..." required>
                <button type="button" class="btn btn-light btn-sm border-0" onclick="hapusFitur(this, '${containerId}')">
                    <i class="bi bi-x text-danger"></i>
                </button>
            </div>
        `;
        wrapper.appendChild(col);
    }

    function hapusFitur(btn, containerId) {
        const container = document.getElementById(containerId);
        if (container.querySelectorAll('.item-fitur').length <= 1) {
            Swal.fire({ icon: 'error', title: 'Oops!', text: 'Minimal harus ada 1 fitur.' });
            return;
        }
        btn.closest('.item-fitur').remove();
    }

    // 5. EDIT DATA
    function editPaket(pkg, features) {
        document.getElementById('edit_name').value = pkg.name;
        document.getElementById('edit_type').value = pkg.type;
        document.getElementById('edit_price').value = pkg.price;
        document.getElementById('formEditPaket').action = `/admin/packages/${pkg.id}`; 

        const container = document.getElementById('list-fitur-edit');
        container.innerHTML = '';
        
        if (features.length > 0) {
            features.forEach(f => tambahBaris('list-fitur-edit', f.feature_name));
        } else {
            tambahBaris('list-fitur-edit');
        }
        
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }

    // 6. SWEETALERT DELETE
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.form-hapus');
            Swal.fire({
                title: 'Hapus Paket?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0089a1',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@endsection