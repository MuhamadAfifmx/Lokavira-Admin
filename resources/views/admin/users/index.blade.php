@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root { --teal-primary: #0089a1; --teal-soft: #e6f3f5; }
    .custom-card { border-radius: 12px; background: #ffffff; border: none; }
    .table thead th { background: #f8f9fa; color: #333; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; border-bottom: 2px solid #eee; }
    .logo-img { width: 42px; height: 42px; border-radius: 10px; object-fit: cover; background: #f0f0f0; }
    .btn-action { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; transition: 0.2s; border: none; background: transparent; }
    .btn-action:hover { background: #f0f0f0; transform: translateY(-2px); }
    .form-label-bold { font-size: 0.85rem; font-weight: 700; margin-bottom: 4px; color: #444; }
    .modal-content-custom { border-radius: 16px; border: none; overflow: hidden; }
    .badge-expired { font-size: 0.7rem; padding: 4px 8px; border-radius: 50px; }
</style>

<div class="shadow-sm custom-card">
    {{-- HEADER --}}
    <div class="p-4 d-flex justify-content-between align-items-center" style="background: var(--teal-primary); border-radius: 12px 12px 0 0;">
        <h1 class="text-white mb-0 fw-bold" style="font-size: 1.2rem;">Manajemen Akun Client</h1>
        <button class="btn btn-light btn-sm px-3 fw-bold" style="color: var(--teal-primary); border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Mitra
        </button>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="p-3 ps-4">Unit Bisnis</th>
                    <th class="p-3">Data PIC</th>
                    <th class="p-3">No. WhatsApp</th>
                    <th class="p-3">Paket</th>
                    <th class="p-3 text-center">Masa Aktif</th>
                    <th class="p-3 text-center pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-dark fw-bold" style="font-size: 0.85rem;">
                @foreach($users as $user)
                <tr>
                    <td class="p-3 ps-4">
                        <div class="d-flex align-items-center">
                            {{-- PERBAIKAN: Tambahkan /public/ di depan asset storage --}}
                            <img src="{{ $user->logo ? asset('public/storage/'.$user->logo) : 'https://ui-avatars.com/api/?name='.$user->business_name }}" class="logo-img border me-3">
                            <span class="text-dark">{{ $user->business_name }}</span>
                        </div>
                    </td>
                    <td class="p-3">
                        <span class="d-block text-dark">{{ $user->representative_name }}</span>
                        <small class="text-muted fw-normal">{{ $user->email }}</small>
                    </td>
                    <td class="p-3">
                        <span class="badge bg-light text-dark border border-secondary px-3 py-2 fw-bold" style="font-size: 0.85rem;">
                            {{ $user->phone_number }}
                        </span>
                    </td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded bg-warning text-dark fw-bold" style="font-size: 0.75rem;">
                            {{ $user->package->name ?? 'No Package' }}
                        </span>
                    </td>
                    <td class="p-3 text-center">
                        @if($user->expires_at)
                            <div class="{{ $user->expires_at->isPast() ? 'text-danger' : 'text-success' }}">
                                {{ $user->expires_at->format('d/m/Y') }}
                                <br>
                                <small class="badge-expired {{ $user->expires_at->isPast() ? 'bg-danger text-white' : 'bg-success-soft text-success' }}" style="font-size: 0.65rem;">
                                    {{ $user->expires_at->isPast() ? 'Expired' : 'Aktif' }}
                                </small>
                            </div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="p-3 text-center pe-4">
                        <div class="d-flex justify-content-center gap-1">
                            {{-- LOGIN SEBAGAI USER --}}
                            <form action="https://lokavira.id/login" method="POST" target="_blank" class="d-inline">
                                <input type="hidden" name="phone_number" value="{{ $user->phone_number }}">
                                <input type="hidden" name="password" value="!#%1234"> 
                                <button type="submit" class="btn-action text-dark" title="Login sebagai User">
                                    <i class="bi bi-box-arrow-in-right fs-5"></i>
                                </button>
                            </form>

                            {{-- DETAIL --}}
                            <button class="btn-action text-info" onclick="showDetail({{ json_encode($user->load('package')) }})" title="Detail">
                                <i class="bi bi-info-circle-fill fs-5"></i>
                            </button>

                            {{-- EDIT --}}
                            <button class="btn-action text-primary" onclick="editUser({{ json_encode($user) }})" title="Edit Data & Masa Aktif">
                                <i class="bi bi-pencil-square fs-5"></i>
                            </button>

                            {{-- RESET PASSWORD --}}
                            <form action="{{ route('admin.users.reset', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="button" class="btn-action text-warning" onclick="confirmReset(this)" title="Reset Password">
                                    <i class="bi bi-shield-lock-fill fs-5"></i>
                                </button>
                            </form>

                            {{-- UPLOAD POST --}}
                            <a href="{{ route('admin.posts.create.id', $user->id) }}" class="btn-action text-success" title="Upload Konten">
                                <i class="bi bi-cloud-arrow-up-fill fs-5"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom shadow">
            <div class="modal-body p-4 text-center">
                <img id="det_logo" src="" class="logo-img mb-3 border shadow-sm" style="width: 80px; height: 80px; border-radius: 20px;">
                <h5 id="det_business" class="fw-bold mb-0 text-dark"></h5>
                <p id="det_username" class="text-muted small mb-4"></p>
                
                <div class="row g-3 text-start bg-light p-3 rounded-3" style="font-size: 0.85rem;">
                    <div class="col-6">
                        <label class="text-muted d-block small">Nama PIC</label>
                        <span id="det_pic" class="fw-bold text-dark"></span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted d-block small">WhatsApp</label>
                        <span id="det_phone" class="fw-bold text-dark"></span>
                    </div>
                    <div class="col-12">
                        <label class="text-muted d-block small">Email</label>
                        <span id="det_email" class="fw-bold text-dark"></span>
                    </div>
                    <div class="col-12">
                        <label class="text-muted d-block small">Alamat</label>
                        <span id="det_address" class="fw-bold text-dark"></span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted d-block small">Paket</label>
                        <span id="det_package" class="badge bg-warning text-dark"></span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted d-block small">Masa Aktif</label>
                        <span id="det_expires" class="fw-bold text-danger"></span>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary w-100 fw-bold mt-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px;">TUTUP</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
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
                        <div class="col-md-6">
                            <label class="form-label-bold">Nama Usaha / Unit Bisnis</label>
                            <input type="text" name="business_name" class="form-control fw-bold" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-bold">Nama PIC (Penanggung Jawab)</label>
                            <input type="text" name="representative_name" class="form-control fw-bold" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-bold">No. WhatsApp (Login)</label>
                            <input type="text" name="phone_number" class="form-control fw-bold" placeholder="08..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-bold">Email Bisnis</label>
                            <input type="email" name="email" class="form-control fw-bold" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-bold">Paket Langganan</label>
                            <select name="package_id" class="form-select fw-bold" required>
                                <option value="" selected disabled>Pilih Paket</option>
                                @foreach($packages as $pkg) <option value="{{ $pkg->id }}">{{ $pkg->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-bold">Password Default</label>
                            <input type="password" name="password" class="form-control fw-bold" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-bold">Alamat Lengkap</label>
                            <textarea name="address" class="form-control fw-bold" rows="2"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-bold">Logo Unit</label>
                            <input type="file" name="logo" class="form-control shadow-sm" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-4 border-0 pt-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-3 text-white" style="background: var(--teal-primary); border: none; border-radius: 12px;">DAFTARKAN MITRA</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-custom shadow">
            <div class="modal-header p-4 border-0 pb-0">
                <h5 class="fw-bold text-dark">Update Data & Masa Aktif</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-bold">Nama Usaha</label>
                            <input type="text" name="business_name" id="edit_business" class="form-control fw-bold" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-bold">Nama PIC</label>
                            <input type="text" name="representative_name" id="edit_representative" class="form-control fw-bold" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-bold">No. WhatsApp</label>
                            <input type="text" name="phone_number" id="edit_phone" class="form-control fw-bold" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-bold">Email Bisnis</label>
                            <input type="email" name="email" id="edit_email" class="form-control fw-bold" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-bold">Paket Langganan</label>
                            <select name="package_id" id="edit_package" class="form-select fw-bold">
                                @foreach($packages as $pkg) <option value="{{ $pkg->id }}">{{ $pkg->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-bold text-danger">Masa Aktif Berakhir Pada</label>
                            <input type="date" name="expires_at" id="edit_expires_at" class="form-control fw-bold border-danger" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-bold">Alamat</label>
                            <textarea name="address" id="edit_address" class="form-control fw-bold" rows="2"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-bold">Update Logo (Kosongkan jika tidak ganti)</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-4 border-0 pt-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-3 text-white" style="background: var(--teal-primary); border: none; border-radius: 12px;">SIMPAN PERUBAHAN</button>
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
        } else {
            document.getElementById('det_expires').innerText = '-';
        }
        
        // PERBAIKAN: Tambahkan /public/ di depan path JavaScript
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
        
        if(user.expires_at) {
            document.getElementById('edit_expires_at').value = user.expires_at.split('T')[0];
        } else {
            document.getElementById('edit_expires_at').value = '';
        }
        
        document.getElementById('formEdit').action = `/admin/users/${user.id}`;
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }

    function confirmReset(btn) {
        Swal.fire({
            title: 'Reset Password?',
            text: "Password akan diacak (6 digit) dan dikirim otomatis via WhatsApp.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => { 
            if (result.isConfirmed) btn.closest('form').submit(); 
        })
    }

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", confirmButtonColor: 'var(--teal-primary)' });
    @endif

    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}", confirmButtonColor: '#d33' });
    @endif
</script>
@endsection