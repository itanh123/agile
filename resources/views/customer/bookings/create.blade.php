@extends('layouts.app')
@section('content')
    <h3>Create Booking</h3>
    <form method="POST" action="{{ route('customer.bookings.store') }}" class="card card-body">
        @csrf
        <div class="row">
            <div class="col-md-4 mb-3"><label>Pet</label><select name="pet_id" class="form-select">@foreach($pets as $pet)<option value="{{ $pet->id }}">{{ $pet->name }}</option>@endforeach</select></div>
            <div class="col-md-4 mb-3"><label>Appointment</label><input type="datetime-local" name="appointment_at" class="form-control"></div>
            <div class="col-md-4 mb-3"><label>Service mode</label><select name="service_mode" class="form-select"><option value="at_store">At store</option><option value="at_home">At home</option></select></div>
            <div class="col-md-4 mb-3"><label>Payment method</label><select name="payment_method" class="form-select"><option value="cash">cash</option><option value="vnpay">vnpay</option><option value="momo">momo</option><option value="transfer">transfer</option></select></div>
            <div class="col-md-4 mb-3"><label>Promotion code</label><input name="promotion_code" class="form-control"></div>
            <div class="col-md-12 mb-3"><label>Services</label>
                <div class="row">
                    @foreach($services as $service)
                        <div class="col-md-4"><label><input type="checkbox" name="service_ids[]" value="{{ $service->id }}"> {{ $service->name }} ({{ number_format($service->price, 0) }})</label></div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-12 mb-3"><label>Note</label><textarea name="note" class="form-control"></textarea></div>
        </div>
        <button class="btn btn-primary">Submit Booking</button>
    </form>
@endsection
