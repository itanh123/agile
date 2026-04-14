@extends('layouts.app')
@section('content')
    <h3>Create Service</h3>
    @include('admin.services.form', ['action' => route('admin.services.store'), 'method' => 'POST', 'service' => null])
@endsection
