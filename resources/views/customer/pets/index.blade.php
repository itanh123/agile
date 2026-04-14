@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h3>My Pets</h3>
        <a href="{{ route('customer.pets.create') }}" class="btn btn-primary">Add Pet</a>
    </div>
    <table class="table table-bordered">
        <thead><tr><th>Name</th><th>Category</th><th>Breed</th><th>Gender</th><th></th></tr></thead>
        <tbody>
        @foreach($pets as $pet)
            <tr>
                <td>{{ $pet->name }}</td><td>{{ $pet->category->name ?? '-' }}</td><td>{{ $pet->breed->name ?? '-' }}</td><td>{{ $pet->gender }}</td>
                <td class="d-flex gap-1">
                    <a class="btn btn-sm btn-info" href="{{ route('customer.pets.show', $pet) }}">View</a>
                    <a class="btn btn-sm btn-warning" href="{{ route('customer.pets.edit', $pet) }}">Edit</a>
                    <form action="{{ route('customer.pets.destroy', $pet) }}" method="POST">@csrf @method('DELETE') <button class="btn btn-sm btn-danger">Delete</button></form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $pets->links() }}
@endsection
