<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Staff Panel' }} - PetCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
    <style>
        :root {
            --staff-sidebar-bg: #2c3e50;
            --staff-sidebar-hover: #34495e;
            --staff-sidebar-active: #18bc9c;
            --staff-primary: #18bc9c;
            --staff-secondary: #3498db;
            --staff-success: #2ecc71;
            --staff-warning: #f39c12;
            --staff-danger: #e74c3c;
            --staff-info: #3498db;
            --staff-dark: #1a252f;
        }
        
        .staff-body {
            background-color: #f5f6fa;
            font-family: 'Outfit', sans-serif;
        }
        
        .staff-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        .staff-sidebar {
            width: 260px;
            background: var(--staff-sidebar-bg);
            color: #ecf0f1;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        
        .sidebar-header {
            padding: 20px;
            background: var(--staff-dark);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #ecf0f1;
        }
        
        .sidebar-brand i {
            font-size: 24px;
            color: var(--staff-primary);
        }
        
        .sidebar-brand span {
            font-weight: 600;
            font-size: 18px;
        }
        
        .sidebar-brand small {
            font-size: 11px;
            background: var(--staff-primary);
            padding: 2px 8px;
            border-radius: 10px;
            color: #fff;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            color: #ecf0f1;
            font-size: 20px;
            cursor: pointer;
            display: none;
        }
        
        .sidebar-nav {
            padding: 15px 0;
        }
        
        .nav-section {
            margin-bottom: 20px;
        }
        
        .nav-section-title {
            display: block;
            padding: 8px 20px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #7f8c8d;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .nav-item:hover {
            background: var(--staff-sidebar-hover);
            color: #ecf0f1;
        }
        
        .nav-item.active {
            background: var(--staff-sidebar-hover);
            color: var(--staff-primary);
            border-left-color: var(--staff-primary);
        }
        
        .nav-item i {
            font-size: 18px;
            width: 24px;
        }
        
        .nav-badge {
            margin-left: auto;
            background: var(--staff-primary);
            color: #fff;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
        }
        
        .nav-badge-warning {
            background: var(--staff-warning);
        }
        
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid #34495e;
            margin-top: auto;
        }
        
        .staff-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
        }
        
        .staff-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--staff-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 16px;
        }
        
        .staff-info {
            flex: 1;
        }
        
        .staff-name {
            display: block;
            font-weight: 500;
            font-size: 14px;
        }
        
        .staff-role {
            display: block;
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .btn-logout {
            background: none;
            border: none;
            color: #bdc3c7;
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.2s;
        }
        
        .btn-logout:hover {
            background: var(--staff-danger);
            color: #fff;
        }
        
        .staff-main {
            flex: 1;
            margin-left: 260px;
        }
        
        .staff-header {
            background: #fff;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .header-toggle {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            display: none;
            color: #2c3e50;
        }
        
        .page-title {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            color: #2c3e50;
        }
        
        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }
        
        .breadcrumb-nav a {
            color: #7f8c8d;
            text-decoration: none;
        }
        
        .breadcrumb-nav a:hover {
            color: var(--staff-primary);
        }
        
        .breadcrumb-nav span {
            color: #bdc3c7;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .header-time {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #7f8c8d;
            font-size: 13px;
        }
        
        .staff-content {
            padding: 30px;
            min-height: calc(100vh - 70px);
        }
        
        .staff-alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .staff-alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 12px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .animate-fade-up {
            animation: fadeUp 0.3s ease;
        }
        
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (max-width: 991px) {
            .staff-sidebar {
                transform: translateX(-100%);
            }
            
            .staff-sidebar.show {
                transform: translateX(0);
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .staff-main {
                margin-left: 0;
            }
            
            .header-toggle {
                display: block;
            }
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }
        
        .stat-card {
            border-radius: 12px;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .stat-card .card-body {
            padding: 25px;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            border-bottom: 2px solid #e9ecef;
            font-weight: 600;
            color: #495057;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table tbody td {
            vertical-align: middle;
        }
        
        .badge {
            font-weight: 500;
            padding: 6px 10px;
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 10px 20px;
            transition: all 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        .quick-action-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.2s;
            border: 2px solid transparent;
            text-decoration: none;
            color: #2c3e50;
            display: block;
        }
        
        .quick-action-card:hover {
            border-color: var(--staff-primary);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .quick-action-card i {
            font-size: 32px;
            color: var(--staff-primary);
            margin-bottom: 10px;
        }
    </style>
</head>
<body class="staff-body">
    <div class="staff-wrapper">
        <!-- Sidebar -->
        <aside class="staff-sidebar" id="staffSidebar">
            <div class="sidebar-header">
                <a href="{{ route('home') }}" class="sidebar-brand">
                    <i class="bi bi-suit-heart-fill"></i>
                    <span>PetCare</span>
                    <small>Staff</small>
                </a>
                <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <span class="nav-section-title">Main</span>
                    <a href="{{ route('staff.dashboard') }}" class="nav-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <span class="nav-section-title">Management</span>
                    <a href="{{ route('staff.bookings.index') }}" class="nav-item {{ request()->routeIs('staff.bookings.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>
                        <span>Bookings</span>
                        @php $pendingCount = \App\Models\Booking::where('staff_id', auth()->id())->whereIn('status', ['pending', 'confirmed', 'processing'])->count(); @endphp
                        @if($pendingCount > 0)
                        <span class="nav-badge nav-badge-warning">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </div>
            </nav>
            
            <div class="sidebar-footer">
                <a href="{{ route('home') }}" class="nav-item">
                    <i class="bi bi-house"></i>
                    <span>Back to Site</span>
                </a>
                <div class="staff-user">
                    <div class="staff-avatar">
                        {{ strtoupper(substr(auth()->user()->fullname ?? auth()->user()->name ?? 'S', 0, 1)) }}
                    </div>
                    <div class="staff-info">
                        <span class="staff-name">{{ auth()->user()->fullname ?? auth()->user()->name }}</span>
                        <span class="staff-role">{{ auth()->user()->role?->name ?? 'Staff' }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-logout" title="Logout">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="staff-main">
            <!-- Top Header -->
            <header class="staff-header">
                <div class="header-left">
                    <button class="header-toggle d-lg-none" id="headerToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="header-title">
                        <h1 class="page-title">{{ $title ?? 'Dashboard' }}</h1>
                        <nav class="breadcrumb-nav">
                            <a href="{{ route('staff.dashboard') }}">Staff</a>
                            @stack('breadcrumbs')
                        </nav>
                    </div>
                </div>
                <div class="header-right">
                    <div class="header-time">
                        <i class="bi bi-clock"></i>
                        <span id="currentTime"></span>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <div class="staff-content">
                @if(session('success'))
                    <div class="alert staff-alert-success animate-fade-up">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert staff-alert-danger animate-fade-up">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert staff-alert-danger animate-fade-up">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        {{ $errors->first() }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateTime() {
            const now = new Date();
            const options = { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
            document.getElementById('currentTime').textContent = now.toLocaleDateString('vi-VN', options);
        }
        updateTime();
        setInterval(updateTime, 60000);
        
        document.getElementById('sidebarToggle')?.addEventListener('click', () => {
            document.getElementById('staffSidebar').classList.remove('show');
        });
        document.getElementById('headerToggle')?.addEventListener('click', () => {
            document.getElementById('staffSidebar').classList.add('show');
        });
    </script>
    @stack('scripts')
</body>
</html>
