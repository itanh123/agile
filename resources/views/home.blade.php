@extends('layouts.app')
@section('content')
    <section class="hero-section animate-fade-up">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="badge-premium mb-3 d-inline-block">The Best Care for Your Pets</span>
                <h1 class="hero-title">Elevate Your Pet's Well-being</h1>
                <p class="hero-subtitle">Experience luxury grooming and specialized care tailored for your furry friends. Book your appointment in seconds.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('services.index') }}" class="btn-premium">Explore Services</a>
                    <a href="{{ route('register') }}" class="btn-outline-premium">Join Our Community</a>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0">
                <div class="position-relative">
                    <div class="glass p-3 rounded-4 shadow-2xl">
                        <img src="{{ asset('hero-pet.png') }}" alt="Pet Care" class="img-fluid rounded-3 shadow-lg">
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    @if($promotions->isNotEmpty())
    <div class="mt-5 pt-4 animate-fade-up">
        <div class="glass-card p-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(168, 85, 247, 0.1)); border: 1px solid rgba(255,255,255,0.05);">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="p-2 rounded bg-primary text-white">
                    <i class="bi bi-stars"></i>
                </div>
                <h3 class="text-white fw-bold mb-0">Ưu Đãi Đặc Biệt</h3>
            </div>
            <div class="row g-4">
                @foreach($promotions as $promo)
                <div class="col-md-4">
                    <div class="p-4 rounded-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-primary px-3 py-2 fw-bold" style="letter-spacing: 1px;">{{ $promo->code }}</span>
                            <span class="text-primary fw-bold fs-4">
                                -{{ $promo->discount_type === 'percent' ? (float)$promo->discount_value.'%' : number_format($promo->discount_value, 0).'đ' }}
                            </span>
                        </div>
                        <h5 class="text-white fw-bold mb-2">{{ $promo->title }}</h5>
                        <p class="text-muted small mb-0">{{ Str::limit($promo->description, 80) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="mt-5 pt-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold text-white mb-2">Our Specialized Services</h2>
                <p class="text-muted">Choose the best for your beloved companions</p>
            </div>
            <a href="{{ route('services.index') }}" class="nav-link-custom">View All services &rarr;</a>
        </div>
        
        <div class="row g-4">
            @foreach($services as $service)
                <div class="col-md-4">
                    <div class="glass-card h-100 p-4 d-flex flex-column">
                        <div class="mb-4">
                            <span class="badge-premium mb-2 d-inline-block">Popular Service</span>
                            <h4 class="text-white fw-bold mb-3">{{ $service->name }}</h4>
                            <p class="text-muted small mb-0 lh-base">{{ Str::limit($service->description, 100) }}</p>
                        </div>
                        <div class="mt-auto pt-4 border-top border-opacity-10 d-flex justify-content-between align-items-center">
                            <span class="fs-5 fw-bold" style="color: var(--primary)">{{ number_format($service->price, 0) }} VND</span>
                            <a href="{{ route('services.show', $service) }}" class="btn-premium py-2 px-3 fs-6">Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
