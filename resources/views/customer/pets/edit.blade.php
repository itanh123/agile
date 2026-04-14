@extends('layouts.app')
@section('content')
    <h3>Edit Pet</h3>
    @include('customer.pets.form', ['action' => route('customer.pets.update', $pet), 'method' => 'PUT', 'pet' => $pet])
@endsection
