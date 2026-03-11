<style>
    :root {
        --teal-primary: #0089a1; 
        --teal-light: #26c6da;
        --sidebar-bg: #e0f2f1; 
        --bg-body: #f1f5f9; 
        --white: #ffffff;
        --black: #0f172a;
        --sidebar-width: 260px;
    }

    /* --- 1. GLOBAL STYLE --- */
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--bg-body);
        margin: 0;
        transition: padding 0.3s ease;
        overflow-x: hidden;
    }

    main {
        padding-top: 100px;
        padding-bottom: 50px;
        transition: all 0.3s ease;
    }

    /* --- 2. NAVBAR & SIDEBAR BASE --- */
    .header-admin {
        position: fixed;
        top: 0; right: 0;
        width: 100%; height: 70px;
        background: var(--white);
        z-index: 1000;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .sidebar {
        position: fixed;
        top: 0;
        width: var(--sidebar-width);
        height: 100vh;
        background: var(--sidebar-bg);
        z-index: 1100;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-right: 1px solid rgba(0, 137, 161, 0.1);
        padding: 30px 15px;
        /* Default sembunyi untuk semua layar, nanti diatur per layar */
        left: calc(-1 * var(--sidebar-width));
    }

    /* --- 3. DESKTOP BEHAVIOR (Min-Width 992px) --- */
    @media (min-width: 992px) {
        /* Default: Muncul */
        body:not(.sidebar-closed) .sidebar {
            left: 0;
        }
        /* Saat ditutup manual di desktop */
        body.sidebar-closed .sidebar {
            left: calc(-1 * var(--sidebar-width));
        }

        body:not(.sidebar-closed) main {
            padding-left: var(--sidebar-width);
        }

        body:not(.sidebar-closed) .header-admin {
            left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
        }
    }

    /* --- 4. MOBILE BEHAVIOR (Max-Width 991px) --- */
    @media (max-width: 991px) {
        /* Default: Sidebar Sembunyi (left: -260px) sudah dari base style */
        
        body.sidebar-open .sidebar {
            left: 0 !important;
        }

        /* Konten di mobile tetap full layar */
        main { padding-left: 0 !important; }
        .header-admin { left: 0 !important; width: 100% !important; }
    }

    /* --- 5. COMPONENT STYLES --- */
    .sidebar-brand {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 10px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(0, 137, 161, 0.1);
    }

    .sidebar-menu { list-style: none; padding: 0; }
    .sidebar-menu li a {
        display: flex; align-items: center; gap: 12px;
        color: #557a82; text-decoration: none;
        padding: 12px 15px; border-radius: 12px;
        font-weight: 600; font-size: 14px; margin-bottom: 5px; transition: 0.2s;
    }

    .sidebar-menu li a:hover, .sidebar-menu li a.active {
        background: var(--teal-primary);
        color: var(--white);
    }

    .hero-title { font-size: 2.2rem; font-weight: 800; }
    .hero-title span {
        background: linear-gradient(135deg, var(--teal-primary) 0%, var(--teal-light) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    

    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px; margin: 40px 0; }
    .stat-item { background: var(--white); padding: 24px; border-radius: 20px; border: 1px solid rgba(0, 137, 161, 0.05); display: flex; align-items: center; gap: 20px; transition: 0.3s; }
    .stat-icon { width: 56px; height: 56px; background: var(--sidebar-bg); color: var(--teal-primary); display: flex; align-items: center; justify-content: center; border-radius: 16px; font-size: 1.4rem; }
    .admin-card { background: var(--white); border-radius: 24px; padding: 40px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.02); }
</style>