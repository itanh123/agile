@extends('layouts.app')
@section('content')
    <h3>Add Pet</h3>
    @include('customer.pets.form', ['action' => route('customer.pets.store'), 'method' => 'POST', 'pet' => null])
@endsection
