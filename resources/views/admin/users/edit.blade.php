@extends('layouts.app')
@section('content')
    <h3>Edit User</h3>
    @include('admin.users.form', ['action' => route('admin.users.update', $user), 'method' => 'PUT', 'user' => $user])
@endsection
