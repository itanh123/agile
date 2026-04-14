@extends('layouts.app')
@section('content')
    <div class="card card-body"><h3>{{ $user->full_name ?? $user->name }}</h3><p>{{ $user->email }}</p></div>
@endsection
