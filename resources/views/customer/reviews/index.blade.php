@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h3>My Reviews</h3>
        <a class="btn btn-primary" href="{{ route('customer.reviews.create') }}">Write Review</a>
    </div>
    <table class="table">
        <thead><tr><th>Booking</th><th>Rating</th><th>Comment</th></tr></thead>
        <tbody>@foreach($reviews as $review)<tr><td>{{ $review->booking_id }}</td><td>{{ $review->rating }}/5</td><td>{{ $review->comment }}</td></tr>@endforeach</tbody>
    </table>
    {{ $reviews->links() }}
@endsection
