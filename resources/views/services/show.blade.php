@extends('layouts.app')
@section('content')
    <div class="row justify-content-center animate-fade-up">
        <div class="col-lg-8">
            <div class="glass-card p-5">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('services.index') }}" class="text-muted text-decoration-none small text-uppercase fw-bold">Services</a></li>
                        <li class="breadcrumb-item active text-white small text-uppercase fw-bold" aria-current="page">{{ $service->name }}</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h1 class="text-white fw-bold mb-2">{{ $service->name }}</h1>
                        <span class="badge-premium">{{ $service->service_type }}</span>
                    </div>
                    <div class="text-end">
                        <div class="text-muted small text-uppercase fw-bold mb-1">Price</div>
                        <h2 class="fw-bold" style="color: var(--primary)">{{ number_format($service->price, 0) }} VND</h2>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="glass p-3 border-0">
                            <div class="text-muted small text-uppercase fw-bold mb-2">Duration</div>
                            <div class="text-white fw-medium d-flex align-items-center">
                                <span class="fs-4 me-2">⏱️</span> {{ $service->duration_minutes }} Minutes
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="glass p-3 border-0">
                            <div class="text-muted small text-uppercase fw-bold mb-2">Service ID</div>
                            <div class="text-white fw-medium">#S{{ str_pad($service->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h5 class="text-white fw-bold mb-3">Service Description</h5>
                    <p class="text-muted lh-lg fs-5">
                        {{ $service->description }}
                    </p>
                </div>

                <div class="pt-4 border-top border-opacity-10 d-flex gap-3">
                    @auth
                        @if(auth()->user()->role?->slug !== 'admin' && auth()->user()->role?->slug !== 'staff')
                            <a href="{{ route('customer.bookings.create') }}" class="btn-premium px-5 py-3">Book This Service Now</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-premium px-5 py-3">Login to Book Now</a>
                    @endauth
                    <a href="{{ route('services.index') }}" class="btn-outline-premium px-4 py-3">Back to catalog</a>
                </div>
            </div>
        </div>
    </div>
@endsection
