@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="header-content mb-4" data-aos="fade-down">
    <h1 class="hero-title">Perbarui <span>Konten</span></h1>
    <p class="text-muted font-medium">Mitra: <strong>{{ $user->business_name }}</strong> (Platform: {{ ucfirst($platform) }})</p>
</div>

<div class="admin-card border-0 shadow-sm p-4" style="border-radius: 20px;">
    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="row g-4 text-dark fw-bold">
            <div class="col-md-4 text-center">
                <label class="small d-block mb-2 text-start">Cover Sekarang</label>
                <img src="{{ asset('storage/'.$post->cover_image) }}" class="img-fluid rounded-4 shadow-sm border" style="max-height: 250px; width: 100%; object-fit: cover;">
            </div>
            <div class="col-md-8">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="small mb-1">Ganti Cover (Kosongkan jika tidak)</label>
                        <input type="file" name="cover_image" class="form-control border-0 bg-light p-3" style="border-radius: 12px;">
                    </div>
                    <div class="col-12">
                        <label class="small mb-1">URL Postingan</label>
                        <input type="url" name="post_url" value="{{ $post->post_url }}" class="form-control border-0 bg-light p-3" style="border-radius: 12px;" required>
                    </div>
                    <div class="col-12">
                        <label class="small mb-1">Tanggal Publikasi</label>
                        <input type="date" name="upload_date" value="{{ $post->upload_date->format('Y-m-d') }}" class="form-control border-0 bg-light p-3" style="border-radius: 12px;" required>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <label class="small mb-1">Views</label>
                <input type="number" name="views" value="{{ $post->views }}" class="form-control border-0 bg-light p-3 numeric-input" style="border-radius: 12px;" min="0" required>
            </div>
            <div class="col-md-3">
                <label class="small mb-1">Likes</label>
                <input type="number" name="likes" value="{{ $post->likes }}" class="form-control border-0 bg-light p-3 numeric-input" style="border-radius: 12px;" min="0">
            </div>
            <div class="col-md-3">
                <label class="small mb-1">Comments</label>
                <input type="number" name="comments" value="{{ $post->comments }}" class="form-control border-0 bg-light p-3 numeric-input" style="border-radius: 12px;" min="0">
            </div>
            <div class="col-md-3">
                <label class="small mb-1">Shares</label>
                <input type="number" name="shares" value="{{ $post->shares }}" class="form-control border-0 bg-light p-3 numeric-input" style="border-radius: 12px;" min="0">
            </div>

            @if($platform == 'tiktok')
            <div class="col-12">
                <div class="p-4 rounded-4 border-0" style="background: #f0f9ff;">
                    <label class="small mb-1 text-primary">Rata-rata Menonton</label>
                    <input type="text" name="avg_watch_time" value="{{ $post->avg_watch_time }}" class="form-control border-0 p-3" style="border-radius: 12px;">
                </div>
            </div>
            @endif

            @if($platform == 'tiktok' || $platform == 'youtube')
            <div class="col-12">
                <div class="p-4 rounded-4 border-0" style="background: #fff1f2;">
                    <label class="small mb-3 text-danger d-block">Demografi Usia (%)</label>
                    <div class="row g-3">
                        @foreach(['18-24', '25-34', '35-44', '45+'] as $age)
                        <div class="col-md-3">
                            <small class="text-muted d-block mb-1">{{ $age }}</small>
                            <input type="text" name="age_demographics[{{ $age }}]" value="{{ $post->age_demographics[$age] ?? '' }}" class="form-control border-0 p-2 numeric-input" style="border-radius: 8px;">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="mt-5 d-flex gap-3">
            <a href="{{ route('admin.posts.index') }}" class="btn btn-light px-4 py-3 fw-bold rounded-4 shadow-sm text-dark" style="text-decoration: none;">BATAL</a>
            <button type="submit" class="btn btn-primary flex-grow-1 py-3 fw-bold shadow-sm" style="background: var(--teal-primary); border: none; border-radius: 15px; color: white;">
                UPDATE DATA PERFORMA
            </button>
        </div>
    </form>
</div>

<script>
    document.querySelectorAll('.numeric-input').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.length > 1 && this.value[0] === '0') {
                this.value = this.value.slice(1);
            }
            if (parseInt(this.value) < 0) this.value = 0;
        });
    });

    @if(session('error_platform'))
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan Validasi',
            text: "{{ session('error_platform') }}",
            confirmButtonColor: '#d33'
        });
    @endif
</script>
@endsection