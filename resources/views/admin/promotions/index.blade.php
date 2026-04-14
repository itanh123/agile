@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between mb-3"><h3>Promotions</h3><a class="btn btn-primary" href="{{ route('admin.promotions.create') }}">Create</a></div>
    <table class="table table-bordered"><thead><tr><th>Code</th><th>Title</th><th>Discount</th><th>Expiry</th><th></th></tr></thead><tbody>
    @foreach($promotions as $promotion)<tr><td>{{ $promotion->code }}</td><td>{{ $promotion->title }}</td><td>{{ $promotion->discount_value }} {{ $promotion->discount_type }}</td><td>{{ $promotion->end_at }}</td><td class="d-flex gap-1"><a class="btn btn-sm btn-warning" href="{{ route('admin.promotions.edit', $promotion) }}">Edit</a><form method="POST" action="{{ route('admin.promotions.destroy', $promotion) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form></td></tr>@endforeach
    </tbody></table>{{ $promotions->links() }}
@endsection
