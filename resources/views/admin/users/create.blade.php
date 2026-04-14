@extends('layouts.app')
@section('content')
    <h3>Create User</h3>
    @include('admin.users.form', ['action' => route('admin.users.store'), 'method' => 'POST', 'user' => null])
@endsection
