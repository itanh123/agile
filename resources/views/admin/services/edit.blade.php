@extends('layouts.app')
@section('content')
    <h3>Edit Service</h3>
    @include('admin.services.form', ['action' => route('admin.services.update', $service), 'method' => 'PUT', 'service' => $service])
@endsection
