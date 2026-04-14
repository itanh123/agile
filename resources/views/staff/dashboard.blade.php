@extends('layouts.app')
@section('content')
    <h3>Staff Dashboard</h3>
    <a class="btn btn-primary mb-3" href="{{ route('staff.bookings.index') }}">Assigned Bookings</a>
    <ul>
        @foreach($bookings as $booking)
            <li>{{ $booking->booking_code }} - {{ $booking->status }}</li>
        @endforeach
    </ul>
@endsection
