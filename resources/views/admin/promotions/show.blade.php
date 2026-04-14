@extends('layouts.app')
@section('content')
    <div class="card card-body"><h3>{{ $promotion->code }}</h3><p>{{ $promotion->title }}</p></div>
@endsection
