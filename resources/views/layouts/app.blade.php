<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Inventaris PMI Semarang</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/pmi.png') }}" type="image/png">

    <!-- Bootstrap & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        :root {
            --pmi-primary: #e60000;
            --pmi-secondary: #9b2c9b;
            --pmi-gradient: linear-gradient(135deg, #e60000, #9b2c9b);
            --sidebar-width: 250px;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            overflow-x: hidden;
            max-width: 100%;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }

        /* Desktop Sidebar */
        .sidebar {
            min-height: 100vh;
            background: var(--pmi-gradient);
            color: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: fixed;
            width: var(--sidebar-width);
            left: 0;
            top: 0;
            z-index: 1000;
        }

        .sidebar-brand {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 10px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 4px;
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff !important;
            transform: translateX(5px);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            background-color: #f5f7fa;
            min-height: 100vh;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            width: calc(100% - var(--sidebar-width));
            max-width: calc(100% - var(--sidebar-width));
            padding-right: 15px;
        }

        /* Card styling */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: #E60000 !important;
        }

        .btn-outline-danger {
            border-color: #E60000;
            color: #E60000;
            transition: all 0.3s ease;
        }

        .btn-outline-danger:hover {
            background-color: #E60000;
            color: white;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 15px;
            background: rgba(230, 0, 0, 0.05);
            border-radius: 8px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--pmi-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }

        .user-name {
            font-weight: 600;
            color: #333;
        }

        .user-role {
            font-size: 0.75rem;
            color: #666;
        }

        .stat-card {
            border-radius: 12px;
            padding: 20px;
            color: white;
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: scale(1.02);
        }

        .stat-card .stat-icon {
            font-size: 3rem;
            opacity: 0.2;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }

        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(230, 0, 0, 0.05);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .btn {
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Page header styling */
        .page-header {
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        }

        .page-header h3 {
            margin: 0;
            color: #333;
            font-weight: 600;
        }

        /* Table improvements */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            border-bottom: 2px solid #e60000;
            color: #333;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 15px 12px;
        }

        /* Action buttons */
        .btn-group .btn {
            border-radius: 6px;
            margin: 0 2px;
        }

        /* Smooth page transitions */
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #e60000;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #cc0000;
        }

        /* Mobile Toggle Button */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #333;
            cursor: pointer;
            padding: 5px 10px;
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        /* ==================== RESPONSIVE STYLES ==================== */
        
        /* Mobile devices */
        @media (max-width: 768px) {
            /* Hide desktop sidebar */
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            /* Adjust main content */
            .main-content {
                margin-left: 0;
                padding-top: 60px;
            }

            /* Show mobile toggle */
            .mobile-toggle {
                display: block;
            }

            /* Show overlay */
            .sidebar-overlay.show {
                display: block;
            }

            /* Mobile navbar */
            .navbar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 100;
                background: white;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }

            .navbar-brand {
                margin-left: 10px;
            }

            /* Mobile user info - simplified */
            .user-info {
                padding: 5px 10px;
                gap: 8px;
            }

            .user-avatar {
                width: 32px;
                height: 32px;
                font-size: 12px;
            }

            .user-details {
                display: none; /* Hide name/role on mobile to save space */
            }

            .user-info::after {
                content: attr(data-name);
                font-weight: 600;
                color: #333;
            }

            /* Page header mobile */
            .page-header {
                padding: 15px;
                margin-bottom: 15px;
            }

            .page-header h3 {
                font-size: 1.1rem;
            }

            /* Cards mobile */
            .stat-card {
                padding: 15px;
                margin-bottom: 10px;
            }

            .stat-card h2 {
                font-size: 1.5rem;
            }

            .stat-card .stat-icon {
                font-size: 2rem;
            }

            /* Tables mobile */
            .table {
                font-size: 0.85rem;
            }

            .table thead th,
            .table tbody td {
                padding: 10px 8px;
            }

            /* Form controls mobile */
            .form-label {
                font-size: 0.9rem;
            }

            .form-control,
            .form-select {
                font-size: 0.9rem;
                padding: 8px 12px;
            }

            /* Buttons mobile */
            .btn {
                padding: 8px 16px;
                font-size: 0.9rem;
            }

            .btn-sm {
                padding: 5px 10px;
                font-size: 0.8rem;
            }

            /* Quick stats row */
            .row.mb-4 > div {
                margin-bottom: 10px;
            }

            /* Loan cards */
            .loan-card {
                margin-bottom: 15px;
            }

            /* Charts */
            #borrowingChart {
                max-height: 250px;
            }

            /* Quick menu */
            .d-grid.gap-2 {
                gap: 10px !important;
            }

            /* Alerts */
            .alert {
                padding: 10px 15px;
                font-size: 0.9rem;
            }

            /* Container padding */
            .container-fluid.px-4 {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            /* Hide some elements on mobile */
            .hide-mobile {
                display: none;
            }
        }

        /* Small mobile */
        @media (max-width: 480px) {
            .page-header h3 {
                font-size: 1rem;
            }

            .stat-card h2 {
                font-size: 1.25rem;
            }

            .table {
                font-size: 0.75rem;
            }
        }

        /* Tablet */
        @media (min-width: 769px) and (max-width: 1024px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
            }

            .sidebar-brand h5 {
                font-size: 1rem;
            }

            .sidebar-brand small {
                font-size: 0.7rem;
            }

            .sidebar-brand img {
                width: 60px !important;
            }
        }

        /* Landscape mobile */
        @media (max-height: 500px) and (orientation: landscape) {
            .sidebar {
                overflow-y: auto;
            }

            .sidebar-brand {
                padding: 10px 15px;
            }

            .nav-link {
                padding: 8px 12px;
            }
        }
    </style>
</head>

<body>
    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="container-fluid">
        <div class="row">
            @auth
                <!-- Mobile Toggle Button -->
                <button class="mobile-toggle position-fixed" id="sidebarToggle" style="z-index: 1001;">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Sidebar -->
                <div class="sidebar" id="sidebar">
                    <div class="sidebar-brand text-center">
                        <img src="{{ asset('images/pmi.png') }}" alt="PMI Logo" width="80" class="mb-2">
                        <h5 class="text-white mb-0">Inventaris</h5>
                        <small class="text-white-50">PMI Semarang</small>
                    </div>
                    <nav class="nav flex-column">
                        @if(auth()->user()->role === 'admin')
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> <span class="nav-text">Dashboard</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                                href="{{ route('categories.index') }}">
                                <i class="fas fa-tags me-2"></i> <span class="nav-text">Kategori</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}"
                                href="{{ route('locations.index') }}">
                                <i class="fas fa-map-marker-alt me-2"></i> <span class="nav-text">Lokasi</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('items.*') ? 'active' : '' }}"
                                href="{{ route('items.index') }}">
                                <i class="fas fa-box me-2"></i> <span class="nav-text">Barang</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('borrowings.*') ? 'active' : '' }}"
                                href="{{ route('borrowings.index') }}">
                                <i class="fas fa-history me-2"></i> <span class="nav-text">Daftar Transaksi</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.peminjaman.index') ? 'active' : '' }}"
                                href="{{ route('admin.peminjaman.index') }}">
                                <i class="fas fa-check-circle me-2"></i> <span class="nav-text">Verif. Peminjaman</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.peminjaman.returns') ? 'active' : '' }}"
                                href="{{ route('admin.peminjaman.returns') }}">
                                <i class="fas fa-clipboard-check me-2"></i> <span class="nav-text">Verif. Pengembalian</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users me-2"></i> <span class="nav-text">Kelola User</span>
                            </a>
                            <a href="{{ route('admin.laporan.barang') }}" class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-chart-column me-2"></i> <span class="nav-text">Laporan</span>
                            </a>
                        @else
                            <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
                                href="{{ route('user.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> <span class="nav-text">Dashboard</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('user.borrow') ? 'active' : '' }}"
                                href="{{ route('user.borrow') }}">
                                <i class="fas fa-plus-circle me-2"></i> <span class="nav-text">Pinjam</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('user.my-borrowings') ? 'active' : '' }}"
                                href="{{ route('user.my-borrowings') }}">
                                <i class="fas fa-hand-holding me-2"></i> <span class="nav-text">Pinjaman Saya</span>
                            </a>
                            <a class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}"
                                href="{{ route('user.profile') }}">
                                <i class="fas fa-user me-2"></i> <span class="nav-text">Profil</span>
                            </a>
                        @endif
                    </nav>
                </div>
            @endauth

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4 py-3">
                    <div class="container-fluid">
                        <button class="mobile-toggle" id="navbarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        <span class="navbar-brand">@yield('title')</span>
                        <div class="navbar-nav ms-auto align-items-center gap-2">
                            @auth
                                <!-- User Info -->
                                <div class="user-info" data-name="{{ auth()->user()->name }}">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ auth()->user()->name }}</div>
                                        <div class="user-role">{{ auth()->user()->role === 'admin' ? 'Admin' : 'Peminjam' }}</div>
                                    </div>
                                </div>
                                
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                </nav>

                <!-- Content -->
                <div class="container-fluid px-4 fade-in">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mobile Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const navbarToggle = document.getElementById('navbarToggle');

            function toggleSidebar() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            }

            // Toggle buttons
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }
            
            if (navbarToggle) {
                navbarToggle.addEventListener('click', toggleSidebar);
            }

            // Close on overlay click
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeSidebar();
                }
            });

            // Close sidebar when clicking nav links on mobile
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            navLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });
        });
    </script>
    
    @yield('scripts')
</body>

</html>
