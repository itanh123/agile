@extends('layouts.app')
@section('content')
    <div class="animate-fade-up">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="badge-premium mb-2 d-inline-block">Welcome back</span>
                <h1 class="text-white fw-bold mb-0">Customer Dashboard</h1>
            </div>
            <p class="text-muted mb-0">Manage your pets and bookings with ease</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <a href="{{ route('customer.pets.index') }}" class="text-decoration-none">
                    <div class="glass-card p-4 text-center">
                        <div class="display-6 mb-3" style="color: var(--primary)">🐾</div>
                        <h5 class="text-white fw-bold">My Pets</h5>
                        <p class="text-muted small mb-0">Manage your furry family members</p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('customer.bookings.index') }}" class="text-decoration-none">
                    <div class="glass-card p-4 text-center">
                        <div class="display-6 mb-3" style="color: var(--secondary)">📅</div>
                        <h5 class="text-white fw-bold">My Bookings</h5>
                        <p class="text-muted small mb-0">View and track your appointments</p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('customer.assistant.index') }}" class="text-decoration-none">
                    <div class="glass-card p-4 text-center">
                        <div class="display-6 mb-3" style="color: var(--accent)">🤖</div>
                        <h5 class="text-white fw-bold">AI Assistant</h5>
                        <p class="text-muted small mb-0">Get instant advice for your pets</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="glass-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="text-white fw-bold mb-0">Recent Bookings</h5>
                <a href="{{ route('customer.bookings.index') }}" class="btn-outline-premium btn-sm py-1">See All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0" style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,0.02)">
                    <thead>
                        <tr class="text-muted">
                            <th class="border-0 font-weight-normal ps-0">Booking Code</th>
                            <th class="border-0 font-weight-normal">Status</th>
                            <th class="border-0 font-weight-normal text-end pe-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td class="border-0 ps-0 fw-medium text-white">{{ $booking->booking_code }}</td>
                                <td class="border-0">
                                    <span class="badge-premium" style="background: rgba(255,255,255,0.05); color: #94a3b8">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="border-0 text-end pe-0">
                                    <a href="{{ route('customer.bookings.show', $booking) }}" class="btn-premium py-1 px-3 fs-6">View Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="border-0 text-center py-4 text-muted">No bookings yet. Start your first journey with us!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
