@extends('layouts.app')
@section('content')
    <div class="row justify-content-center py-5 animate-fade-up">
        <div class="col-md-8 col-lg-6">
            <div class="glass-card p-5">
                <div class="text-center mb-5">
                    <h2 class="text-white fw-bold mb-2">Create Account</h2>
                    <p class="text-muted">Join our community and start caring for your pets</p>
                </div>
                
                <form method="POST" action="{{ route('register.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Full Name</label>
                            <input name="full_name" class="form-control-premium w-100" placeholder="John Doe" required autofocus>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Email Address</label>
                            <input name="email" type="email" class="form-control-premium w-100" placeholder="john@example.com" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Phone Number</label>
                            <input name="phone" class="form-control-premium w-100" placeholder="+84 ...">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Address</label>
                            <input name="address" class="form-control-premium w-100" placeholder="Your location">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Password</label>
                            <input name="password" type="password" class="form-control-premium w-100" placeholder="••••••••" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Confirm Password</label>
                            <input name="password_confirmation" type="password" class="form-control-premium w-100" placeholder="••••••••" required>
                        </div>
                    </div>
                    
                    <button class="btn-premium w-100 py-3 mb-4 mt-2">Create My Account</button>
                    
                    <div class="text-center">
                        <p class="text-muted small mb-0">Already have an account? <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: var(--primary)">Sign In</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
