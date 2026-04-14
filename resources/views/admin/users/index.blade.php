@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between mb-3"><h3>Users</h3><a class="btn btn-primary" href="{{ route('admin.users.create') }}">Create</a></div>
    <form class="row mb-2"><div class="col-md-4"><input name="q" class="form-control" value="{{ request('q') }}" placeholder="Search"></div></form>
    <table class="table table-bordered"><thead><tr><th>Name</th><th>Email</th><th>Role</th><th></th></tr></thead><tbody>
        @foreach($users as $user)<tr><td>{{ $user->full_name ?? $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->role->name ?? '-' }}</td><td class="d-flex gap-1"><a class="btn btn-sm btn-warning" href="{{ route('admin.users.edit', $user) }}">Edit</a><form method="POST" action="{{ route('admin.users.destroy', $user) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form></td></tr>@endforeach
    </tbody></table>{{ $users->links() }}
@endsection
