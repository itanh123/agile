@extends('layouts.app')
@section('content')
    <h3>Customer Dashboard</h3>
    <div class="mb-3 d-flex gap-2">
        <a class="btn btn-primary" href="{{ route('customer.pets.index') }}">My Pets</a>
        <a class="btn btn-primary" href="{{ route('customer.bookings.index') }}">My Bookings</a>
        <a class="btn btn-primary" href="{{ route('customer.assistant.index') }}">AI Assistant</a>
    </div>
    <div class="card">
        <div class="card-body">
            <h5>Recent bookings</h5>
            <ul class="mb-0">
                @forelse($bookings as $booking)
                    <li><a href="{{ route('customer.bookings.show', $booking) }}">{{ $booking->booking_code }}</a> - {{ $booking->status }}</li>
                @empty
                    <li>No bookings yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
