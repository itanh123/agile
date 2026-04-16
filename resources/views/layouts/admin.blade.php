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
                    <span class="nav-section-title">Main</span>
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <span class="nav-section-title">Management</span>
                    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Users</span>
                        @php $userCount = \App\Models\User::count(); @endphp
                        <span class="nav-badge">{{ $userCount }}</span>
                    </a>
                    <a href="{{ route('admin.services.index') }}" class="nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        <i class="bi bi-gem"></i>
                        <span>Services</span>
                        @php $serviceCount = \App\Models\Service::count(); @endphp
                        <span class="nav-badge">{{ $serviceCount }}</span>
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="nav-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>
                        <span>Bookings</span>
                        @php $bookingCount = \App\Models\Booking::whereIn('status', ['pending', 'confirmed'])->count(); @endphp
                        @if($bookingCount > 0)
                        <span class="nav-badge nav-badge-warning">{{ $bookingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.promotions.index') }}" class="nav-item {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                        <i class="bi bi-tag"></i>
                        <span>Promotions</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <span class="nav-section-title">Access Control</span>
                    <a href="{{ route('admin.roles.index') }}" class="nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-check"></i>
                        <span>Roles</span>
                    </a>
                    <a href="{{ route('admin.permissions.index') }}" class="nav-item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                        <i class="bi bi-key"></i>
                        <span>Permissions</span>
                    </a>
                    <a href="{{ route('admin.users.permissions.index') }}" class="nav-item {{ request()->routeIs('admin.users.permissions.*') ? 'active' : '' }}">
                        <i class="bi bi-person-check"></i>
                        <span>User Permissions</span>
                    </a>
                    <a href="{{ route('admin.teams.index') }}" class="nav-item {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}">
                        <i class="bi bi-diagram-3"></i>
                        <span>Teams</span>
                    </a>
                    <a href="{{ route('admin.access-matrix.index') }}" class="nav-item {{ request()->routeIs('admin.access-matrix.*') ? 'active' : '' }}">
                        <i class="bi bi-grid-3x3"></i>
                        <span>Access Matrix</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <span class="nav-section-title">Analytics</span>
                    <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i>
                        <span>Reports</span>
                    </a>
                </div>
            </nav>
            
            <div class="sidebar-footer">
                <a href="{{ route('home') }}" class="nav-item">
                    <i class="bi bi-house"></i>
                    <span>Back to Site</span>
                </a>
                <div class="admin-user">
                    <div class="admin-avatar">
                        {{ strtoupper(substr(auth()->user()->fullname ?? auth()->user()->username ?? 'A', 0, 1)) }}
                    </div>
                    <div class="admin-info">
                        <span class="admin-name">{{ auth()->user()->fullname ?? auth()->user()->username }}</span>
                        <span class="admin-role">{{ auth()->user()->role?->name ?? 'Admin' }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="btn-logout" title="Logout">
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
                        <h1 class="page-title">{{ $title ?? 'Dashboard' }}</h1>
                        <nav class="breadcrumb-nav">
                            <a href="{{ route('admin.dashboard') }}">Admin</a>
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
