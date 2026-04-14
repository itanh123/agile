@extends('layouts.app')
@section('content')
    <div class="row justify-content-center py-5 animate-fade-up">
        <div class="col-md-5 mt-5">
            <div class="glass-card p-5">
                <div class="text-center mb-5">
                    <h2 class="text-white fw-bold mb-2">Welcome Back</h2>
                    <p class="text-muted">Enter your credentials to access your account</p>
                </div>
                
                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Email Address</label>
                        <input name="email" type="email" class="form-control-premium w-100" placeholder="name@example.com" required autofocus>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-0">Password</label>
                            <a href="#" class="text-muted small text-decoration-none hover-primary">Forgot password?</a>
                        </div>
                        <input name="password" type="password" class="form-control-premium w-100" placeholder="••••••••" required>
                    </div>
                    
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input bg-dark border-secondary" id="remember">
                        <label class="form-check-label text-muted small" for="remember">Remember me for 30 days</label>
                    </div>
                    
                    <button class="btn-premium w-100 py-3 mb-4">Sign In</button>
                    
                    <div class="text-center">
                        <p class="text-muted small mb-0">Don't have an account? <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color: var(--primary)">Register here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
