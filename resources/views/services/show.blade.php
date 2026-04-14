@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-body">
            <h3>{{ $service->name }}</h3>
            <p>Type: {{ $service->service_type }}</p>
            <p>Duration: {{ $service->duration_minutes }} mins</p>
            <p>{{ $service->description }}</p>
            <p class="fw-bold">{{ number_format($service->price, 0) }} VND</p>
            @auth
                @if(auth()->user()->role?->slug !== 'admin' && auth()->user()->role?->slug !== 'staff')
                    <a href="{{ route('customer.bookings.create') }}" class="btn btn-primary">Book now</a>
                @endif
            @endauth
        </div>
    </div>
@endsection
