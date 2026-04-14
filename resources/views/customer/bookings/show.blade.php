@extends('layouts.app')
@section('content')
    <h3>Booking {{ $booking->booking_code }}</h3>
    <div class="card mb-3"><div class="card-body">
        <p>Status: <strong>{{ $booking->status }}</strong></p>
        <p>Payment: {{ $booking->payment_status }} / {{ $booking->payment_method }}</p>
        <p>Total: {{ number_format($booking->total_amount, 0) }} VND</p>
        <p>Appointment: {{ $booking->appointment_at }}</p>
        <form method="POST" action="{{ route('customer.bookings.reschedule', $booking) }}" class="mb-2">@csrf @method('PATCH')
            <div class="input-group"><input type="datetime-local" name="appointment_at" class="form-control"><button class="btn btn-warning">Reschedule</button></div>
        </form>
        <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}">@csrf @method('PATCH')
            <button class="btn btn-danger" @disabled(in_array($booking->status, ['completed','cancelled']))>Cancel booking</button>
        </form>
    </div></div>
    <div class="row">
        <div class="col-md-6">
            <div class="card"><div class="card-body"><h5>Services</h5><ul>@foreach($booking->services as $service)<li>{{ $service->name }}</li>@endforeach</ul></div></div>
        </div>
        <div class="col-md-6">
            <div class="card"><div class="card-body"><h5>Progress Images</h5><ul>@forelse($booking->images as $image)<li>{{ $image->image_path }} - {{ $image->caption }}</li>@empty<li>No images</li>@endforelse</ul></div></div>
        </div>
    </div>
@endsection
