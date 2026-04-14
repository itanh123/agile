@extends('layouts.app')
@section('content')
    <h3>Reports</h3>
    <div class="row">
        <div class="col-md-4"><div class="card card-body"><h6>Booking Status Summary</h6><ul>@foreach($statusCounts as $s)<li>{{ $s->status }}: {{ $s->total }}</li>@endforeach</ul></div></div>
        <div class="col-md-4"><div class="card card-body"><h6>Revenue by Month</h6><ul>@foreach($revenueByMonth as $r)<li>{{ $r->month }}: {{ number_format($r->total, 0) }}</li>@endforeach</ul></div></div>
        <div class="col-md-4"><div class="card card-body"><h6>Most Used Services</h6><ul>@foreach($mostUsedServices as $s)<li>{{ $s->name }}: {{ $s->total }}</li>@endforeach</ul></div></div>
    </div>
@endsection
