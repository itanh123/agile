@extends('layouts.app')
@section('content')
    <h3>Services</h3>
    <form class="row mb-3">
        <div class="col-md-4"><input name="q" class="form-control" placeholder="Search service" value="{{ request('q') }}"></div>
        <div class="col-md-2"><button class="btn btn-outline-primary">Search</button></div>
    </form>
    <div class="row">
        @foreach($services as $service)
            <div class="col-md-4 mb-3">
                <div class="card h-100"><div class="card-body">
                    <h5>{{ $service->name }}</h5>
                    <p>{{ $service->description }}</p>
                    <p class="fw-bold">{{ number_format($service->price, 0) }} VND</p>
                    <a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-primary">View</a>
                </div></div>
            </div>
        @endforeach
    </div>
    {{ $services->links() }}
@endsection
