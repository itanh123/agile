@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between mb-3"><h3>Services</h3><a class="btn btn-primary" href="{{ route('admin.services.create') }}">Create</a></div>
    <table class="table table-bordered"><thead><tr><th>Name</th><th>Type</th><th>Price</th><th>Status</th><th></th></tr></thead><tbody>
        @foreach($services as $service)<tr><td>{{ $service->name }}</td><td>{{ $service->service_type }}</td><td>{{ number_format($service->price, 0) }}</td><td>{{ $service->is_active ? 'active':'inactive' }}</td><td class="d-flex gap-1"><a class="btn btn-sm btn-warning" href="{{ route('admin.services.edit', $service) }}">Edit</a><form method="POST" action="{{ route('admin.services.destroy', $service) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form></td></tr>@endforeach
    </tbody></table>{{ $services->links() }}
@endsection
