<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- 1. PROTEKSI SEO: Izinkan indeks tapi jangan simpan cache (agar aman) --}}
    <meta name="robots" content="index, follow, noarchive">
    <title>Login | LokaVira</title>
    
    {{-- 2. FAVICON: Logo LokaVira di Tab Browser --}}
    <link rel="icon" type="image/png" href="{{ asset('logolv.png') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        html, body {
            height: 100%;
            margin: 0;
            background-color: #0089a1; 
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* ... CSS Anda lainnya tetap sama ... */
        .card-rect {
            background-color: #ffffff;
            border-radius: 12px; 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        .text-teal-lokavira { color: #0089a1; }
        .text-teal-soft { color: rgba(0, 137, 161, 0.6); }
        .btn-lokavira {
            background-color: #0089a1;
            transition: all 0.3s ease;
            border-radius: 6px;
        }
        .btn-lokavira:hover { background-color: #00768a; }
        .input-custom {
            background-color: rgba(0, 137, 161, 0.03);
            border: 1px solid rgba(0, 137, 161, 0.15);
            color: #000000;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .input-custom:focus {
            background-color: #ffffff;
            border-color: #0089a1;
            outline: none;
        }
        .toggle-password:focus { outline: none; }
    </style>
</head>
<body class="antialiased flex items-center justify-center p-4">

<div class="w-full max-w-[750px] flex flex-col md:flex-row card-rect overflow-hidden">
    {{-- Sisi Kiri (Desktop) --}}
    <div class="hidden md:flex md:w-1/2 p-8 flex-col justify-center items-center text-center bg-white">
        <h2 class="text-2xl font-bold leading-tight text-gray-800 mb-6">
            Kelola <span class="text-teal-lokavira">Social Media</span><br>
            <span class="text-lg font-medium text-gray-500">Secara profesional</span>
        </h2>
        
        <svg xmlns="http://www.w3.org/2000/svg" class="w-32 h-32 text-teal-lokavira opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
        </svg>
    </div>

    {{-- Sisi Kanan (Form) --}}
    <div class="w-full md:w-1/2 p-8 md:p-10 bg-white flex flex-col justify-center">
        <div class="flex flex-col items-center mb-8">
            {{-- LOGO LOKAVIRA --}}
            <img src="{{ asset('logolv.png') }}" alt="Logo LokaVira" class="w-20 h-20 rounded-lg mb-3 object-cover shadow-sm">
            <h3 class="text-2xl font-extrabold text-teal-lokavira tracking-tight italic">LokaVira</h3>
        </div>

        <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold mb-1.5 ml-0.5 text-teal-soft">Nomor Telepon</label>
                <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number') }}" 
                       class="w-full px-4 py-2.5 input-custom font-medium text-sm" 
                       placeholder="08123456789" required autofocus>
                @error('phone_number')
                    <span class="text-red-500 text-[10px] mt-1 block"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold mb-1.5 ml-0.5 text-teal-soft">Kata sandi</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-2.5 input-custom font-medium text-sm pr-10"
                           placeholder="••••••••">
                    <button type="button" onclick="toggleLoginPassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-teal-soft hover:text-teal-lokavira transition-colors toggle-password">
                        <i id="eye-icon" class="bi bi-eye fs-6"></i>
                    </button>
                </div>
                @error('password')
                    <span class="text-red-500 text-[10px] mt-1 block"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <button type="submit" class="w-full btn-lokavira text-white font-bold py-3 active:scale-[0.98] text-sm mt-2 transition-all shadow-md">
                Masuk
            </button>
        </form>
    </div>
</div>

{{-- 3. SCRIPT ANTI-INTIP (Proteksi Kode Login) --}}
<script>
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.onkeydown = function(e) {
        if (e.keyCode == 123 || (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 74 || e.keyCode == 67)) || (e.ctrlKey && e.keyCode == 85)) {
            return false;
        }
    };
</script>

<script>
    function toggleLoginPassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    document.getElementById('loginForm').addEventListener('submit', function() {
        let btn = this.querySelector('button');
        btn.innerHTML = '<div class="flex items-center justify-center text-xs"><svg class="animate-spin h-4 w-4 text-white mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memverifikasi...</div>';
    });

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Data tidak sesuai.',
            confirmButtonColor: '#0089a1',
            customClass: { popup: 'rounded-lg text-sm' }
        });
    @endif
</script>
</body>
</html>