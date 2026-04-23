<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }} - PetCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <a href="{{ route('home') }}" class="sidebar-brand">
                    <i class="bi bi-suit-heart-fill"></i>
                    <span>PetCare</span>
                    <small>Admin</small>
                </a>
                <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <span class="nav-section-title">Chính</span>
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Bảng điều khiển</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <span class="nav-section-title">Quản lý</span>
                    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Người dùng</span>
                        @php $userCount = \App\Models\User::count(); @endphp
                        <span class="nav-badge">{{ $userCount }}</span>
                    </a>
                    <a href="{{ route('admin.services.index') }}" class="nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        <i class="bi bi-gem"></i>
                        <span>Dịch vụ</span>
                        @php $serviceCount = \App\Models\Service::count(); @endphp
                        <span class="nav-badge">{{ $serviceCount }}</span>
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="nav-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>
                        <span>Lịch hẹn</span>
                        @php $bookingCount = \App\Models\Booking::whereIn('status', ['pending', 'confirmed'])->count(); @endphp
                        @if($bookingCount > 0)
                        <span class="nav-badge nav-badge-warning">{{ $bookingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.promotions.index') }}" class="nav-item {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                        <i class="bi bi-tag"></i>
                        <span>Khuyến mãi</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <span class="nav-section-title">Kiểm soát truy cập</span>
                    <a href="{{ route('admin.roles.index') }}" class="nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-check"></i>
                        <span>Vai trò</span>
                    </a>
                    <a href="{{ route('admin.permissions.index') }}" class="nav-item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                        <i class="bi bi-key"></i>
                        <span>Quyền hạn</span>
                    </a>
                    <a href="{{ route('admin.users.permissions.index') }}" class="nav-item {{ request()->routeIs('admin.users.permissions.*') ? 'active' : '' }}">
                        <i class="bi bi-person-check"></i>
                        <span>Phân quyền User</span>
                    </a>
                    <a href="{{ route('admin.teams.index') }}" class="nav-item {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}">
                        <i class="bi bi-diagram-3"></i>
                        <span>Đội ngũ</span>
                    </a>
                    <a href="{{ route('admin.access-matrix.index') }}" class="nav-item {{ request()->routeIs('admin.access-matrix.*') ? 'active' : '' }}">
                        <i class="bi bi-grid-3x3"></i>
                        <span>Ma trận quyền</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <span class="nav-section-title">Thống kê</span>
                    <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i>
                        <span>Báo cáo</span>
                    </a>
                </div>
            </nav>
            
            <div class="sidebar-footer">
                <a href="{{ route('home') }}" class="nav-item">
                    <i class="bi bi-house"></i>
                    <span>Về trang chủ</span>
                </a>
                <div class="admin-user">
                    <div class="admin-avatar">
                        {{ strtoupper(substr(auth()->user()->fullname ?? auth()->user()->username ?? 'A', 0, 1)) }}
                    </div>
                    <div class="admin-info">
                        <span class="admin-name">{{ auth()->user()->fullname ?? auth()->user()->username }}</span>
                        <span class="admin-role">{{ auth()->user()->role?->name ?? 'Quản trị' }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="btn-logout" title="Đăng xuất">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="header-toggle d-lg-none" id="headerToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="header-title">
                        <h1 class="page-title">{{ $title ?? 'Bảng điều khiển' }}</h1>
                        <nav class="breadcrumb-nav">
                            <a href="{{ route('admin.dashboard') }}">Quản trị</a>
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
            <div class="admin-content">
                @if(session('success'))
                    <div class="alert admin-alert-success animate-fade-up">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert admin-alert-danger animate-fade-up">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert admin-alert-danger animate-fade-up">
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
        // Current time
        function updateTime() {
            const now = new Date();
            const options = { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
            document.getElementById('currentTime').textContent = now.toLocaleDateString('vi-VN', options);
        }
        updateTime();
        setInterval(updateTime, 60000);
        
        // Sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', () => {
            document.getElementById('adminSidebar').classList.remove('show');
        });
        document.getElementById('headerToggle')?.addEventListener('click', () => {
            document.getElementById('adminSidebar').classList.add('show');
        });
    </script>
</body>
</html>
