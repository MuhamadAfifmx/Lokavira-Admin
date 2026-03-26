@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root { --teal-primary: #0089a1; --teal-soft: #e6f3f5; }
    
    .fw-800 { font-weight: 800; }
    .custom-card { border-radius: 20px; background: #ffffff; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    
    /* Tabel Styling */
    .table thead th { 
        background: #f8f9fa; 
        color: #6c757d; 
        font-weight: 700; 
        text-transform: uppercase; 
        font-size: 0.7rem; 
        padding: 15px 20px;
        border: none;
    }

    .logo-img { width: 42px; height: 42px; border-radius: 12px; object-fit: cover; background: #f0f0f0; border: 1px solid #eee; }

    /* Action Wrapper: Berjejer Horizontal */
    .action-wrapper {
        display: flex;
        gap: 12px; /* Jarak antar aksi yang pas */
        justify-content: flex-end;
        align-items: flex-start;
    }

    /* Button Action dengan Teks di Bawah */
    .btn-action-vertical {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        padding: 0;
        transition: all 0.2s;
        min-width: 45px;
        text-decoration: none;
    }

    .btn-action-vertical:hover {
        transform: translateY(-3px);
    }

    .icon-circle {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #f8f9fa;
        border: 1px solid #eee;
        margin-bottom: 4px;
        transition: 0.2s;
    }

    .btn-action-vertical:hover .icon-circle {
        background: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    .action-label {
        font-size: 9px; /* Ukuran teks kecil tapi bold agar rapi */
        font-weight: 800;
        text-transform: uppercase;
        color: #666;
        letter-spacing: 0.3px;
    }

    /* Warna Icon Spesifik */
    .text-login { color: #444; }
    .text-detail { color: #0dcaf0; }
    .text-edit { color: #0d6efd; }
    .text-reset { color: #ffc107; }
    .text-post { color: #198754; }

    .modal-content-custom { border-radius: 20px; border: none; }
    .form-label-bold { font-size: 0.85rem; font-weight: 700; color: #444; }
</style>

<div class="container-fluid py-4" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-800 text-dark mb-1">Manajemen Mitra</h4>
            <p class="text-muted small mb-0">Kelola akses mitra Lokavira dengan aksi cepat.</p>
        </div>
        <button class="btn btn-primary px-4 py-2 shadow-sm border-0 d-flex align-items-center" 
                style="border-radius: 12px; background: var(--teal-primary);" 
                data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-2"></i> Tambah Mitra
        </button>
    </div>

    <div class="custom-card shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Unit Bisnis</th>
                        <th>PIC & WhatsApp</th>
                        <th>Paket</th>
                        <th class="text-center">Masa Aktif</th>
                        <th class="text-end pe-4">Kelola Data</th>
                    </tr>
                </thead>
                <tbody class="text-dark fw-bold" style="font-size: 0.85rem;">
                    @foreach($users as $user)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->logo ? asset('public/storage/'.$user->logo) : 'https://ui-avatars.com/api/?name='.$user->business_name }}" class="logo-img me-3 shadow-sm">
                                <div>
                                    <span class="d-block text-dark">{{ $user->business_name }}</span>
                                    <small class="text-muted fw-normal" style="font-size: 10px;">ID: LV-{{ $user->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="d-block">{{ $user->representative_name }}</span>
                            <small class="text-teal-primary fw-bold"><i class="bi bi-whatsapp"></i> {{ $user->phone_number }}</small>
                        </td>
                        <td>
                            <span class="px-2 py-1 rounded bg-warning text-dark fw-bold" style="font-size: 0.7rem;">
                                {{ $user->package->name ?? 'No Package' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($user->expires_at)
                                <div class="{{ $user->expires_at->isPast() ? 'text-danger' : 'text-success' }}" style="font-size: 12px;">
                                    {{ $user->expires_at->format('d/m/Y') }}
                                </div>
                                <span class="badge {{ $user->expires_at->isPast() ? 'bg-danger' : 'bg-success' }} text-white" style="font-size: 0.6rem; border-radius: 50px;">
                                    {{ $user->expires_at->isPast() ? 'Expired' : 'Aktif' }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="action-wrapper">
                                <form action="https://client.lokavira.id/login" method="POST" target="_blank" class="m-0">
                                    <input type="hidden" name="phone_number" value="{{ $user->phone_number }}">
                                    <input type="hidden" name="password" value="!#%1234"> 
                                    <button type="submit" class="btn-action-vertical">
                                        <div class="icon-circle"><i class="bi bi-box-arrow-in-right text-login"></i></div>
                                        <span class="action-label">Login</span>
                                    </button>
                                </form>

                                <button class="btn-action-vertical" onclick="showDetail({{ json_encode($user->load('package')) }})">
                                    <div class="icon-circle"><i class="bi bi-info-circle-fill text-detail"></i></div>
                                    <span class="action-label">Detail</span>
                                </button>

                                <button class="btn-action-vertical" onclick="editUser({{ json_encode($user) }})">
                                    <div class="icon-circle"><i class="bi bi-pencil-square text-edit"></i></div>
                                    <span class="action-label">Edit</span>
                                </button>

                                <form action="{{ route('admin.users.reset', $user->id) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="button" class="btn-action-vertical" onclick="confirmReset(this)">
                                        <div class="icon-circle"><i class="bi bi-shield-lock-fill text-reset"></i></div>
                                        <span class="action-label">Reset</span>
                                    </button>
                                </form>

                                <a href="{{ route('admin.posts.create.id', $user->id) }}" class="btn-action-vertical">
                                    <div class="icon-circle"><i class="bi bi-cloud-arrow-up-fill text-post"></i></div>
                                    <span class="action-label">Post</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom shadow">
            <div class="modal-body p-4 text-center">
                <img id="det_logo" src="" class="logo-img mb-3 shadow-sm" style="width: 80px; height: 80px; border-radius: 20px;">
                <h5 id="det_business" class="fw-bold mb-1 text-dark"></h5>
                <p id="det_username" class="text-muted small mb-4"></p>
                <div class="row g-3 text-start bg-light p-3 rounded-4" style="font-size: 0.85rem;">
                    <div class="col-6"><label class="text-muted d-block small">Nama PIC</label><span id="det_pic" class="fw-bold text-dark text-capitalize"></span></div>
                    <div class="col-6"><label class="text-muted d-block small">WhatsApp</label><span id="det_phone" class="fw-bold text-dark"></span></div>
                    <div class="col-12"><label class="text-muted d-block small">Email Bisnis</label><span id="det_email" class="fw-bold text-dark"></span></div>
                    <div class="col-12"><label class="text-muted d-block small">Alamat</label><span id="det_address" class="fw-bold text-dark"></span></div>
                    <div class="col-6"><label class="text-muted d-block small">Paket</label><span id="det_package" class="badge bg-warning text-dark px-2"></span></div>
                    <div class="col-6"><label class="text-muted d-block small">Masa Aktif</label><span id="det_expires" class="fw-bold text-danger"></span></div>
                </div>
                <button type="button" class="btn btn-secondary w-100 fw-bold mt-4 py-2 border-0" data-bs-dismiss="modal" style="border-radius: 12px; background: #6c757d;">TUTUP</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH & EDIT --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-custom shadow">
            <div class="modal-header p-4 border-0 pb-0">
                <h5 class="fw-bold text-dark">Registrasi Mitra Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label-bold">Nama Usaha</label><input type="text" name="business_name" class="form-control fw-bold" required></div>
                        <div class="col-md-6"><label class="form-label-bold">Nama PIC</label><input type="text" name="representative_name" class="form-control fw-bold" required></div>
                        <div class="col-md-6"><label class="form-label-bold">WhatsApp</label><input type="text" name="phone_number" class="form-control fw-bold" required></div>
                        <div class="col-md-6"><label class="form-label-bold">Email Bisnis</label><input type="email" name="email" class="form-control fw-bold" required></div>
                        <div class="col-md-6"><label class="form-label-bold">Paket</label>
                            <select name="package_id" class="form-select fw-bold" required>
                                <option value="" disabled selected>Pilih Paket</option>
                                @foreach($packages as $pkg) <option value="{{ $pkg->id }}">{{ $pkg->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label-bold">Password</label><input type="password" name="password" class="form-control fw-bold" required></div>
                        <div class="col-md-12"><label class="form-label-bold">Alamat Lengkap</label><textarea name="address" class="form-control fw-bold" rows="2"></textarea></div>
                        <div class="col-md-12"><label class="form-label-bold">Logo</label><input type="file" name="logo" class="form-control shadow-sm" accept="image/*"></div>
                    </div>
                </div>
                <div class="modal-footer p-4 border-0 pt-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-3 text-white border-0" style="background: var(--teal-primary); border-radius: 12px;">DAFTARKAN MITRA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-custom shadow">
            <div class="modal-header p-4 border-0 pb-0">
                <h5 class="fw-bold text-dark">Update Data Mitra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label-bold">Nama Usaha</label><input type="text" name="business_name" id="edit_business" class="form-control fw-bold" required></div>
                        <div class="col-md-6"><label class="form-label-bold">Nama PIC</label><input type="text" name="representative_name" id="edit_representative" class="form-control fw-bold" required></div>
                        <div class="col-md-6"><label class="form-label-bold">WhatsApp</label><input type="text" name="phone_number" id="edit_phone" class="form-control fw-bold" required></div>
                        <div class="col-md-6"><label class="form-label-bold">Email Bisnis</label><input type="email" name="email" id="edit_email" class="form-control fw-bold" required></div>
                        <div class="col-md-6"><label class="form-label-bold">Paket</label>
                            <select name="package_id" id="edit_package" class="form-select fw-bold">
                                @foreach($packages as $pkg) <option value="{{ $pkg->id }}">{{ $pkg->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label-bold text-danger">Berakhir Pada</label><input type="date" name="expires_at" id="edit_expires_at" class="form-control fw-bold border-danger" required></div>
                        <div class="col-md-12"><label class="form-label-bold">Alamat</label><textarea name="address" id="edit_address" class="form-control fw-bold" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer p-4 border-0 pt-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-3 text-white border-0" style="background: var(--teal-primary); border-radius: 12px;">SIMPAN PERUBAHAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showDetail(user) {
        document.getElementById('det_business').innerText = user.business_name;
        document.getElementById('det_username').innerText = 'Username: ' + user.phone_number;
        document.getElementById('det_pic').innerText = user.representative_name;
        document.getElementById('det_phone').innerText = user.phone_number;
        document.getElementById('det_email').innerText = user.email;
        document.getElementById('det_address').innerText = user.address || '-';
        document.getElementById('det_package').innerText = user.package ? user.package.name : 'No Package';
        if(user.expires_at) {
            let d = new Date(user.expires_at);
            document.getElementById('det_expires').innerText = d.toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'});
        } else { document.getElementById('det_expires').innerText = '-'; }
        let logoPath = user.logo ? `/public/storage/${user.logo}` : `https://ui-avatars.com/api/?name=${user.business_name}`;
        document.getElementById('det_logo').src = logoPath;
        new bootstrap.Modal(document.getElementById('modalDetail')).show();
    }
    function editUser(user) {
        document.getElementById('edit_business').value = user.business_name;
        document.getElementById('edit_representative').value = user.representative_name;
        document.getElementById('edit_address').value = user.address || '';
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_phone').value = user.phone_number;
        document.getElementById('edit_package').value = user.package_id;
        if(user.expires_at) { document.getElementById('edit_expires_at').value = user.expires_at.split('T')[0]; }
        else { document.getElementById('edit_expires_at').value = ''; }
        document.getElementById('formEdit').action = `/admin/users/${user.id}`;
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }
    function confirmReset(btn) {
        Swal.fire({
            title: 'Reset Password?', text: "Password baru akan dikirim via WhatsApp.", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ffc107', confirmButtonText: 'Ya, Reset!'
        }).then((result) => { if (result.isConfirmed) btn.closest('form').submit(); })
    }
</script>
@endsection