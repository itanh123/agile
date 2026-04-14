@extends('layouts.app')
@section('content')
    <h3>AI Assistant</h3>
    <div class="card mb-3"><div class="card-body" style="max-height:400px; overflow:auto;">
        @forelse($messages as $message)
            <div class="mb-2"><strong>{{ strtoupper($message->sender) }}:</strong> {{ $message->content }}</div>
        @empty
            <p>No messages yet.</p>
        @endforelse
    </div></div>
    <form method="POST" action="{{ route('customer.assistant.store') }}" class="input-group">
        @csrf
        <input name="content" class="form-control" placeholder="Type your message...">
        <button class="btn btn-primary">Send</button>
    </form>
@endsection
