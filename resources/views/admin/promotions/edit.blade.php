@extends('layouts.app')
@section('content')
    <h3>Edit Promotion</h3>
    @include('admin.promotions.form', ['action' => route('admin.promotions.update', $promotion), 'method' => 'PUT', 'promotion' => $promotion])
@endsection
