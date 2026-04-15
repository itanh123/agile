@extends('layouts.app')
@section('content')
    <div class="animate-fade-up">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="badge-premium mb-2 d-inline-block">Member Area</span>
                <h1 class="text-white fw-bold mb-0">My Furry Friends</h1>
            </div>
            <a href="{{ route('customer.pets.create') }}" class="btn-premium">
                <i class="bi bi-plus-lg me-1"></i> Add New Pet
            </a>
        </div>

        <div class="row g-4">
            @forelse($pets as $pet)
                <div class="col-md-6 col-lg-4">
                    <div class="glass-card p-4 h-100 d-flex flex-column">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="display-5" style="filter: drop-shadow(0 0 8px rgba(99, 102, 241, 0.3))">
                                {{ $pet->category?->slug === 'dog' ? '🐶' : ($pet->category?->slug === 'cat' ? '🐱' : '🐾') }}
                            </div>
                            <div>
                                <h4 class="text-white fw-bold mb-0">{{ $pet->name }}</h4>
                                <span class="text-muted small fw-medium">{{ $pet->breed?->name ?? 'Unknown Breed' }}</span>
                            </div>
                        </div>

                        <div class="glass p-3 mb-4 flex-grow-1 border-0">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-muted small text-uppercase fw-bold mb-1">Gender</div>
                                    <div class="text-white fw-medium">
                                        {{ $pet->gender === 'male' ? '♂️ Male' : ($pet->gender === 'female' ? '♀️ Female' : 'Other') }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small text-uppercase fw-bold mb-1">Status</div>
                                    <div class="badge-premium py-1 text-center w-100">Healthy</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 pt-3 border-top border-opacity-10">
                            <a class="btn-outline-premium btn-sm flex-fill text-center py-2" href="{{ route('customer.pets.show', $pet) }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a class="btn-outline-premium btn-sm flex-fill text-center py-2 text-warning" href="{{ route('customer.pets.edit', $pet) }}" style="border-color: rgba(255, 193, 7, 0.2)">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('customer.pets.destroy', $pet) }}" method="POST" class="flex-fill" onsubmit="return confirm('Remove your pet from our registry?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn-outline-premium btn-sm w-100 text-danger py-2" style="border-color: rgba(220, 53, 69, 0.2)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="glass p-5 text-center">
                        <div class="display-3 mb-3 opacity-20">🐾</div>
                        <h4 class="text-white">No pets registered yet</h4>
                        <p class="text-muted mb-4">Start by adding your first furry companion!</p>
                        <a href="{{ route('customer.pets.create') }}" class="btn-premium">Add My First Pet</a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $pets->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
