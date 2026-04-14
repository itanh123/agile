@extends('layouts.app')
@section('content')
    <h3>Write Review</h3>
    <form method="POST" action="{{ route('customer.reviews.store') }}" class="card card-body">
        @csrf
        <div class="mb-3"><label>Booking</label><select name="booking_id" class="form-select">@foreach($bookings as $booking)<option value="{{ $booking->id }}">{{ $booking->booking_code }}</option>@endforeach</select></div>
        <div class="mb-3"><label>Rating</label><input name="rating" type="number" min="1" max="5" class="form-control"></div>
        <div class="mb-3"><label>Title</label><input name="title" class="form-control"></div>
        <div class="mb-3"><label>Comment</label><textarea name="comment" class="form-control"></textarea></div>
        <button class="btn btn-primary">Submit</button>
    </form>
@endsection
