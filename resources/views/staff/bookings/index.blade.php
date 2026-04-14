@extends('layouts.app')
@section('content')
    <h3>Assigned Bookings</h3>
    <table class="table">
        <thead><tr><th>Code</th><th>Customer</th><th>Status</th><th></th></tr></thead>
        <tbody>@foreach($bookings as $booking)<tr><td>{{ $booking->booking_code }}</td><td>{{ $booking->user->full_name ?? $booking->user->name }}</td><td>{{ $booking->status }}</td><td><a href="{{ route('staff.bookings.show', $booking) }}" class="btn btn-sm btn-info">View</a></td></tr>@endforeach</tbody>
    </table>
    {{ $bookings->links() }}
@endsection
