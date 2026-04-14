@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4>Register</h4>
                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf
                        <div class="mb-3"><label>Full name</label><input name="full_name" class="form-control" required></div>
                        <div class="mb-3"><label>Email</label><input name="email" type="email" class="form-control" required></div>
                        <div class="mb-3"><label>Phone</label><input name="phone" class="form-control"></div>
                        <div class="mb-3"><label>Address</label><input name="address" class="form-control"></div>
                        <div class="mb-3"><label>Password</label><input name="password" type="password" class="form-control" required></div>
                        <div class="mb-3"><label>Confirm password</label><input name="password_confirmation" type="password" class="form-control" required></div>
                        <button class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
