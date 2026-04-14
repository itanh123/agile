@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h4>Login</h4>
                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf
                        <div class="mb-3"><label>Email</label><input name="email" type="email" class="form-control" required></div>
                        <div class="mb-3"><label>Password</label><input name="password" type="password" class="form-control" required></div>
                        <button class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
