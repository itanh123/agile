@extends('layouts.app')
@section('content')
    <div class="card card-body"><h3>{{ $service->name }}</h3><p>{{ $service->description }}</p></div>
@endsection
