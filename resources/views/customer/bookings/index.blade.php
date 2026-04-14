@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h3>My Bookings</h3>
        <a href="{{ route('customer.bookings.create') }}" class="btn btn-primary">Create Booking</a>
    </div>
    <table class="table table-bordered">
        <thead><tr><th>Code</th><th>Pet</th><th>Status</th><th>Appointment</th><th>Total</th><th></th></tr></thead>
        <tbody>
        @foreach($bookings as $booking)
            <tr>
                <td>{{ $booking->booking_code }}</td><td>{{ $booking->pet->name ?? '-' }}</td><td>{{ $booking->status }}</td>
                <td>{{ $booking->appointment_at }}</td><td>{{ number_format($booking->total_amount, 0) }}</td>
                <td><a class="btn btn-sm btn-info" href="{{ route('customer.bookings.show', $booking) }}">Detail</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $bookings->links() }}
@endsection
