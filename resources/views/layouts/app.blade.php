<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pet Care' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Pet Care</a>
        <div class="navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('services.index') }}">Services</a></li>
                @auth
                    @if(auth()->user()->role?->slug === 'admin')
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a></li>
                    @elseif(auth()->user()->role?->slug === 'staff')
                        <li class="nav-item"><a class="nav-link" href="{{ route('staff.dashboard') }}">Staff</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('customer.dashboard') }}">Customer</a></li>
                    @endif
                @endauth
            </ul>
            <div class="d-flex gap-2">
                @auth
                    <form action="{{ route('logout') }}" method="POST">@csrf<button class="btn btn-outline-light btn-sm">Logout</button></form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-light btn-sm">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
<div class="container py-4">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    @yield('content')
</div>
</body>
</html>
