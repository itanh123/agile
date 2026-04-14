@extends('layouts.app')
@section('content')
    <div class="card"><div class="card-body">
        <h3>{{ $pet->name }}</h3>
        <p>Category: {{ $pet->category->name ?? '-' }}</p>
        <p>Breed: {{ $pet->breed->name ?? '-' }}</p>
        <p>Gender: {{ $pet->gender }}</p>
        <p>Health: {{ $pet->health_status }}</p>
        <p>Notes: {{ $pet->notes }}</p>
    </div></div>
@endsection
