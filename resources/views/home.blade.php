@extends('layouts.app')
@section('content')
    <div class="p-4 mb-4 bg-light rounded-3">
        <h1>Pet Care Booking Management</h1>
        <p class="mb-0">Book grooming and pet care services online with real-time status updates.</p>
    </div>
    <div class="row">
        @foreach($services as $service)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5>{{ $service->name }}</h5>
                        <p>{{ $service->description }}</p>
                        <p class="fw-bold">{{ number_format($service->price, 0) }} VND</p>
                        <a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-primary">Detail</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
