@extends('layouts.app')
@section('content')
    <h3>My Profile</h3>
    <form method="POST" action="{{ route('customer.profile.update') }}" class="card card-body">
        @csrf @method('PUT')
        <div class="mb-3"><label>Full name</label><input name="full_name" class="form-control" value="{{ old('full_name', auth()->user()->full_name ?? auth()->user()->name) }}"></div>
        <div class="mb-3"><label>Phone</label><input name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}"></div>
        <div class="mb-3"><label>Address</label><input name="address" class="form-control" value="{{ old('address', auth()->user()->address) }}"></div>
        <button class="btn btn-primary">Save</button>
    </form>
@endsection
