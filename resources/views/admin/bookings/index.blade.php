@extends('layouts.app')
@section('content')
    <h3>All Bookings</h3>
    <form class="row mb-3">
        <div class="col-md-3"><input name="customer" value="{{ request('customer') }}" placeholder="Customer" class="form-control"></div>
        <div class="col-md-3"><select name="status" class="form-select"><option value="">All status</option>@foreach(['pending','confirmed','processing','completed','cancelled'] as $status)<option value="{{ $status }}" @selected(request('status')==$status)>{{ $status }}</option>@endforeach</select></div>
        <div class="col-md-2"><button class="btn btn-outline-primary">Filter</button></div>
    </form>
    <table class="table table-bordered"><thead><tr><th>Code</th><th>Customer</th><th>Pet</th><th>Status</th><th>Staff</th><th></th></tr></thead><tbody>
    @foreach($bookings as $booking)<tr><td>{{ $booking->booking_code }}</td><td>{{ $booking->user->full_name ?? $booking->user->name }}</td><td>{{ $booking->pet->name ?? '-' }}</td><td>{{ $booking->status }}</td><td>{{ $booking->staff->full_name ?? '-' }}</td><td><a class="btn btn-sm btn-info" href="{{ route('admin.bookings.show', $booking) }}">Detail</a></td></tr>@endforeach
    </tbody></table>{{ $bookings->links() }}
@endsection
