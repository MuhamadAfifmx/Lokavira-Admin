<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    {{-- PROTEKSI SEO: Agar tidak muncul di Google --}}
    <meta name="robots" content="noindex, nofollow, noarchive">
    <meta name="googlebot" content="noindex, nofollow">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- FAVICON: Logo LokaVira di Tab Browser --}}
    <link rel="icon" type="image/png" href="{{ asset('logolv.png') }}">
    
    <title>Admin Dashboard | LokaVira</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('layouts.section') 
</head>
<body>

    @include('layouts.header')

    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- PROTEKSI KODE: Anti Klik Kanan & Inspect --}}
    <script>
        document.addEventListener('contextmenu', e => e.preventDefault());
        document.onkeydown = function(e) {
            if (e.keyCode == 123 || 
               (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 74 || e.keyCode == 67)) || 
               (e.ctrlKey && e.keyCode == 85)) {
                return false;
            }
        };
    </script>

    <script>
        const btnToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const body = document.body;
        const sidebarLinks = document.querySelectorAll('.sidebar-menu a');

        btnToggle.addEventListener('click', function() {
            if (window.innerWidth >= 992) {
                body.classList.toggle('sidebar-closed');
            } else {
                body.classList.toggle('sidebar-open');
                overlay.style.display = body.classList.contains('sidebar-open') ? 'block' : 'none';
            }
        });

        overlay.addEventListener('click', closeSidebarMobile);

        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    closeSidebarMobile();
                }
            });
        });

        function closeSidebarMobile() {
            body.classList.remove('sidebar-open');
            overlay.style.display = 'none';
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                overlay.style.display = 'none';
                body.classList.remove('sidebar-open');
            }
        });
    </script>
</body>
</html>