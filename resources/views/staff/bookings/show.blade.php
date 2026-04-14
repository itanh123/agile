@extends('layouts.app')
@section('content')
    <h3>Booking {{ $booking->booking_code }}</h3>
    <p>Status: {{ $booking->status }}</p>
    <form method="POST" action="{{ route('staff.bookings.update-status', $booking) }}" class="card card-body mb-3">
        @csrf @method('PATCH')
        <div class="row">
            <div class="col-md-4"><select name="status" class="form-select"><option>confirmed</option><option>processing</option><option>completed</option><option>cancelled</option></select></div>
            <div class="col-md-6"><input name="note" class="form-control" placeholder="Status note"></div>
            <div class="col-md-2"><button class="btn btn-primary">Update</button></div>
        </div>
    </form>
    <form method="POST" action="{{ route('staff.bookings.upload-image', $booking) }}" class="card card-body mb-3">
        @csrf
        <h6>Upload Progress Image (path)</h6>
        <div class="row"><div class="col-md-5"><input name="image_path" class="form-control" placeholder="storage/pets/.."></div><div class="col-md-3"><input name="stage" class="form-control" placeholder="Before"></div><div class="col-md-3"><input name="caption" class="form-control" placeholder="Caption"></div><div class="col-md-1"><button class="btn btn-success">Add</button></div></div>
    </form>
    <form method="POST" action="{{ route('staff.bookings.add-note', $booking) }}" class="card card-body">
        @csrf
        <h6>Add Condition Note</h6>
        <textarea name="note" class="form-control mb-2"></textarea>
        <button class="btn btn-secondary">Save Note</button>
    </form>
@endsection
