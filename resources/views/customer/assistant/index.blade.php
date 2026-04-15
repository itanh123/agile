@extends('layouts.app')
@section('content')
    <div class="animate-fade-up">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="badge-premium mb-2 d-inline-block">AI Powered</span>
                <h1 class="text-white fw-bold mb-0">Pet Care Assistant</h1>
            </div>
            <p class="text-muted mb-0">Ask anything about your pet's health and wellness</p>
        </div>

        <div class="glass-card d-flex flex-column" style="height: 600px;">
            <!-- Chat Messages -->
            <div class="flex-grow-1 p-4 overflow-auto d-flex flex-column gap-3" id="chat-container">
                @forelse($messages as $message)
                    <div class="d-flex {{ $message->sender === 'user' ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="p-3 px-4 rounded-4 shadow-sm {{ $message->sender === 'user' ? 'bg-primary text-white' : 'glass border-0 text-white' }}" style="max-width: 80%; backdrop-filter: blur(5px);">
                            <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.65rem; color: {{ $message->sender === 'user' ? 'rgba(255,255,255,0.7)' : 'var(--primary)' }} !important;">
                                {{ $message->sender === 'user' ? 'You' : 'Assistant' }}
                            </div>
                            <div class="lh-relaxed">{{ $message->content }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center my-auto">
                        <div class="display-4 opacity-10 mb-3">🤖</div>
                        <h5 class="text-muted">Hello! How can I help you today?</h5>
                        <p class="text-muted small">Ask me about grooming tips, nutrition, or booking info.</p>
                    </div>
                @endforelse
            </div>

            <!-- Input Form -->
            <div class="p-4 border-top border-opacity-10">
                <form method="POST" action="{{ route('customer.assistant.store') }}" class="d-flex gap-2">
                    @csrf
                    <input name="content" class="form-control-premium flex-grow-1" placeholder="Type your message here..." required autocomplete="off">
                    <button class="btn-premium px-4">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Scroll to bottom of chat
        const container = document.getElementById('chat-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    </script>
@endsection
