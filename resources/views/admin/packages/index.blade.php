@extends('layouts.admin')

@section('content')
<div class="shadow-sm overflow-hidden" style="border-radius: 12px; background: #ffffff;">
    <div class="p-4 d-flex justify-content-between align-items-center" 
         style="background: var(--teal-primary); position: relative; z-index: 2; box-shadow: 0 4px 12px rgba(0, 137, 161, 0.25);">
        <div>
            <h1 class="text-white mb-0 fw-800" style="font-size: 1.5rem;">Manajemen Paket</h1>
        </div>
        <button type="button" class="btn btn-light px-3 py-2 fw-bold shadow-sm d-flex align-items-center" 
                style="border-radius: 8px; color: var(--teal-primary); font-size: 14px;" 
                data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle-fill me-2"></i> Tambah Paket
        </button>
    </div>

    <div class="p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="p-3 ps-4 border-0 text-muted small fw-bold">PLATFORM</th>
                        <th class="p-3 border-0 text-muted small fw-bold">PAKET</th>
                        <th class="p-3 border-0 text-muted small fw-bold">HARGA</th>
                        <th class="p-3 border-0 text-muted small fw-bold">FITUR</th>
                        <th class="p-3 text-center border-0 pe-4 text-muted small fw-bold">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $pkg)
                    <tr>
                        <td class="p-3 ps-4 border-0">
                            <span class="badge" style="background: #e0f2f1; color: #0089a1; border-radius: 6px; padding: 6px 10px; font-weight: 700; border: 1px solid rgba(0,137,161,0.2);">{{ $pkg->type }}</span>
                        </td>
                        <td class="p-3 border-0 fw-bold text-dark">{{ $pkg->name }}</td>
                        <td class="p-3 border-0 fw-bold text-teal-primary">Rp {{ number_format($pkg->price, 0, ',', '.') }}</td>
                        <td class="p-3 border-0">
                            <button class="btn btn-sm px-3 text-white fw-bold d-flex align-items-center" 
                                    style="border-radius: 6px; background: var(--teal-primary); font-size: 11px; border:none;"
                                    onclick="showFeatures('{{ $pkg->name }}', {{ $pkg->features }})">
                                <i class="bi bi-eye-fill me-1"></i> Detail
                            </button>
                        </td>
                        <td class="p-3 border-0 text-center pe-4">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm shadow-sm border-0 text-dark" 
                                        style="background-color: #ffc107; border-radius: 8px; padding: 6px 12px;"
                                        onclick="editPaket({{ $pkg }}, {{ $pkg->features }})">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <form action="{{ route('admin.packages.destroy', $pkg->id) }}" method="POST" class="form-hapus">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-delete shadow-sm border-0 text-white" 
                                            style="background-color: #dc3545; border-radius: 8px; padding: 6px 12px;">
                                        <i class="bi bi-trash3-fill"></i>
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
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0 p-3">
                <h6 class="fw-bold text-teal-primary mb-0" id="fiturTitle">Detail Fitur</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <ul class="list-group list-group-flush" id="featureList"></ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold text-dark mb-0">Buat Paket Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.packages.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2 uppercase">Nama Paket</label>
                            <input type="text" name="name" class="form-control bg-light border-0 p-3" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2 uppercase">Platform</label>
                            <select name="type" class="form-select bg-light border-0 p-3">
                                <option value="Instagram">Instagram</option>
                                <option value="TikTok">TikTok</option>
                                <option value="YouTube">YouTube</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2 uppercase">Harga (Rp)</label>
                            <input type="number" name="price" class="form-control bg-light border-0 p-3" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="small fw-bold text-muted uppercase">Daftar Fitur</label>
                            <button type="button" onclick="tambahBaris('list-fitur-tambah')" class="btn btn-sm text-teal-primary fw-bold p-0">
                                <i class="bi bi-plus-circle-fill"></i> Tambah
                            </button>
                        </div>
                        <div id="list-fitur-tambah" class="row g-2">
                            <div class="col-md-4 item-fitur">
                                <input type="text" name="features[]" class="form-control bg-light border-0 p-2 small" placeholder="Fitur 1" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light flex-fill p-3 fw-bold" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-primary flex-fill p-3 fw-bold shadow-sm" style="background: var(--teal-primary); border:none;">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold text-dark mb-0">Edit Informasi Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditPaket" method="POST">
                @csrf 
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2 uppercase">Nama Paket</label>
                            <input type="text" name="name" id="edit_name" class="form-control bg-light border-0 p-3" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2 uppercase">Platform</label>
                            <select name="type" id="edit_type" class="form-select bg-light border-0 p-3">
                                <option value="Instagram">Instagram</option>
                                <option value="TikTok">TikTok</option>
                                <option value="YouTube">YouTube</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold text-muted mb-2 uppercase">Harga (Rp)</label>
                            <input type="number" name="price" id="edit_price" class="form-control bg-light border-0 p-3" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="small fw-bold text-muted uppercase">Edit Fitur</label>
                            <button type="button" onclick="tambahBaris('list-fitur-edit')" class="btn btn-sm text-teal-primary fw-bold p-0">
                                <i class="bi bi-plus-circle-fill"></i> Tambah
                            </button>
                        </div>
                        <div id="list-fitur-edit" class="row g-2"></div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light flex-fill p-3 fw-bold" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-primary flex-fill p-3 fw-bold shadow-sm" style="background: var(--teal-primary); border:none;">PERBARUI DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showFeatures(packageName, features) {
        document.getElementById('fiturTitle').innerText = 'Fitur: ' + packageName;
        const list = document.getElementById('featureList');
        list.innerHTML = '';
        features.forEach(f => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex align-items-center border-0 px-0 small text-dark';
            li.innerHTML = `<i class="bi bi-patch-check-fill text-teal-primary me-2"></i> ${f.feature_name}`;
            list.appendChild(li);
        });
        new bootstrap.Modal(document.getElementById('modalFitur')).show();
    }

    function tambahBaris(containerId, value = '') {
        const wrapper = document.getElementById(containerId);
        const col = document.createElement('div');
        col.className = 'col-md-4 position-relative item-fitur';
        col.innerHTML = `
            <input type="text" name="features[]" value="${value}" class="form-control bg-light border-0 p-2 pe-4 small" style="border-radius: 6px;" placeholder="Tulis fitur..." required>
            <button type="button" class="btn-close position-absolute end-0 top-50 translate-middle-y me-2" style="font-size: 0.6rem; opacity: 0.8;" onclick="hapusFitur(this, '${containerId}')"></button>
        `;
        wrapper.appendChild(col);
    }

    function hapusFitur(btn, containerId) {
        const container = document.getElementById(containerId);
        const jumlahFitur = container.querySelectorAll('.item-fitur').length;
        if (jumlahFitur <= 1) {
            Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Minimal satu fitur harus tersisa.' });
            return;
        }
        btn.parentElement.remove();
    }

    function editPaket(pkg, features) {
        document.getElementById('edit_name').value = pkg.name;
        document.getElementById('edit_type').value = pkg.type;
        document.getElementById('edit_price').value = pkg.price;
        
        // --- FIXED URL (Sesuai Route Prefix Admin) ---
        document.getElementById('formEditPaket').action = `/admin/packages/${pkg.id}`; 

        const container = document.getElementById('list-fitur-edit');
        container.innerHTML = '';
        features.forEach(f => tambahBaris('list-fitur-edit', f.feature_name));
        if (features.length === 0) tambahBaris('list-fitur-edit');
        
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }

    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.form-hapus');
            Swal.fire({
                title: 'Hapus Paket?',
                text: "Data akan hilang permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        });
    });
</script>
@endsection