@extends('layouts.app')
@section('content')
    <h3>Create Promotion</h3>
    @include('admin.promotions.form', ['action' => route('admin.promotions.store'), 'method' => 'POST', 'promotion' => null])
@endsection
