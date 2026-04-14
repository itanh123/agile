@extends('layouts.app')
@section('content')
    <h3>Booking {{ $booking->booking_code }}</h3>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-body mb-3">
                <h6>Assign Staff</h6>
                <form method="POST" action="{{ route('admin.bookings.assign-staff', $booking) }}">@csrf @method('PATCH')
                    <div class="input-group"><select name="staff_id" class="form-select">@foreach($staffUsers as $staff)<option value="{{ $staff->id }}">{{ $staff->full_name ?? $staff->name }}</option>@endforeach</select><button class="btn btn-primary">Assign</button></div>
                </form>
            </div>
            <div class="card card-body">
                <h6>Update Status</h6>
                <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">@csrf @method('PATCH')
                    <div class="mb-2"><select name="status" class="form-select">@foreach(['pending','confirmed','processing','completed','cancelled'] as $status)<option value="{{ $status }}" @selected($booking->status==$status)>{{ $status }}</option>@endforeach</select></div>
                    <div class="mb-2"><input name="note" class="form-control" placeholder="Note"></div>
                    <button class="btn btn-warning">Update</button>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-body">
                <h6>Services</h6>
                <ul>@foreach($booking->services as $service)<li>{{ $service->name }}</li>@endforeach</ul>
            </div>
        </div>
    </div>
@endsection
