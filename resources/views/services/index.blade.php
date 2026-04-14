@extends('layouts.app')
@section('content')
    <div class="animate-fade-up">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="badge-premium mb-2 d-inline-block">Full Catalog</span>
                <h1 class="text-white fw-bold mb-0">Our Premium Services</h1>
            </div>
            <p class="text-muted mb-0">Discover the perfect care plan for your pet</p>
        </div>

        <div class="glass p-4 mb-5">
            <form action="{{ route('services.index') }}" method="GET" class="row g-3">
                <div class="col-md-9">
                    <input name="q" class="form-control-premium w-100" placeholder="What service are you looking for?" value="{{ request('q') }}">
                </div>
                <div class="col-md-3">
                    <button class="btn-premium w-100 h-100">Search Catalog</button>
                </div>
            </form>
        </div>

        <div class="row g-4">
            @foreach($services as $service)
                <div class="col-md-4">
                    <div class="glass-card h-100 p-4 d-flex flex-column">
                        <div class="mb-4">
                            <h4 class="text-white fw-bold mb-3">{{ $service->name }}</h4>
                            <p class="text-muted small mb-0 lh-base">{{ Str::limit($service->description, 120) }}</p>
                        </div>
                        <div class="mt-auto pt-4 border-top border-opacity-10 d-flex justify-content-between align-items-center">
                            <span class="fs-5 fw-bold" style="color: var(--primary)">{{ number_format($service->price, 0) }} VND</span>
                            <a href="{{ route('services.show', $service) }}" class="btn-premium py-2 px-3 fs-6">Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $services->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
