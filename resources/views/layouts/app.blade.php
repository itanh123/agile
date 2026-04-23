<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pet Care' }} - Premium Pet Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 text-white" href="{{ route('home') }}">
            <span style="color: var(--primary)">Pet</span>Care
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link-custom {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">Services</a>
                </li>
                @auth
                    @if(auth()->user()->role?->slug === 'admin')
                        <li class="nav-item"><a class="nav-link-custom" href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                    @elseif(auth()->user()->role?->slug === 'staff')
                        <li class="nav-item"><a class="nav-link-custom" href="{{ route('staff.dashboard') }}">Staff Portal</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link-custom {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                    @endif
                @endauth
            </ul>
            <div class="d-flex align-items-center gap-3">
                @auth
                    <div class="dropdown">
                        <a href="#" class="nav-link-custom dropdown-toggle" data-bs-toggle="dropdown">
                            {{ auth()->user()->fullname ?? auth()->user()->username }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark glass shadow-lg border-0">
                            <li><a class="dropdown-item" href="{{ route('customer.profile.edit') }}">Profile</a></li>
                            <li><hr class="dropdown-divider opacity-10"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-outline-premium btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn-premium btn-sm">Join Now</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main class="container py-5">
    @if(session('success'))
        <div class="alert glass border-0 text-success mb-4 animate-fade-up">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert glass border-0 text-danger mb-4 animate-fade-up">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="alert glass border-0 text-info mb-4 animate-fade-up">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2"></i>
                {{ session('info') }}
            </div>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert glass border-0 text-danger mb-4 animate-fade-up">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ $errors->first() }}
            </div>
        </div>
    @endif

    @yield('content')
</main>

<footer class="container py-5 text-center">
    <div class="opacity-50 text-sm mb-3">
        &copy; {{ date('Y') }} PetCare Premium. All rights reserved.
    </div>
    <div class="d-flex justify-center gap-4 text-muted">
        <a href="#" class="nav-link-custom p-0">Privacy</a>
        <a href="#" class="nav-link-custom p-0">Terms</a>
        <a href="#" class="nav-link-custom p-0">Contact</a>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
