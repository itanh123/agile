@extends('layouts.app')
@section('content')
    <h3>Admin Dashboard</h3>
    <div class="row mb-3">
        <div class="col-md-3"><div class="card card-body">Users: {{ $totalUsers }}</div></div>
        <div class="col-md-3"><div class="card card-body">Bookings: {{ $totalBookings }}</div></div>
        <div class="col-md-3"><div class="card card-body">Services: {{ $totalServices }}</div></div>
        <div class="col-md-3"><div class="card card-body">Revenue: {{ number_format($totalRevenue, 0) }}</div></div>
    </div>
    <div class="mb-3 d-flex gap-2">
        <a class="btn btn-primary" href="{{ route('admin.users.index') }}">Users</a>
        <a class="btn btn-primary" href="{{ route('admin.services.index') }}">Services</a>
        <a class="btn btn-primary" href="{{ route('admin.bookings.index') }}">Bookings</a>
        <a class="btn btn-primary" href="{{ route('admin.promotions.index') }}">Promotions</a>
        <a class="btn btn-primary" href="{{ route('admin.reports.index') }}">Reports</a>
    </div>
@endsection
