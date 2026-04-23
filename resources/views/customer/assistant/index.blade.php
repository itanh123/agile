@extends('layouts.app')
@section('content')
    <div class="animate-fade-up">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="badge-premium mb-2 d-inline-block">AI Powered</span>
                <h1 class="text-white fw-bold mb-0">Pet Care Assistant</h1>
            </div>
            <p class="text-muted mb-0">Hỏi đáp về dịch vụ, chăm sóc thú cưng</p>
        </div>

        <div class="glass-card d-flex flex-column" style="height: 600px;">
            <!-- Chat Messages -->
            <div class="flex-grow-1 p-4 overflow-auto d-flex flex-column gap-3" id="chat-container">
                @forelse($messages as $message)
                    <div class="d-flex {{ $message->sender === 'user' ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="p-3 px-4 rounded-4 shadow-sm {{ $message->sender === 'user' ? 'bg-primary text-white' : 'glass border-0 text-white' }}" style="max-width: 80%; backdrop-filter: blur(5px);">
                            <div class="small text-uppercase fw-bold mb-1" style="font-size: 0.65rem; {{ $message->sender === 'user' ? 'color: rgba(255,255,255,0.7);' : 'color: var(--primary);' }}">
                                {{ $message->sender === 'user' ? 'Bạn' : 'Trợ lý AI' }}
                            </div>
                            <div class="lh-relaxed">{!! nl2br(e($message->content)) !!}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center my-auto">
                        <div class="display-4 opacity-10 mb-3">🤖</div>
                        <h5 class="text-muted">Xin chào! Tôi có thể giúp gì cho bạn?</h5>
                        <p class="text-muted small">Hỏi về dịch vụ, giá cả, đặt lịch, chăm sóc thú cưng...</p>
                    </div>
                @endforelse
            </div>

            <!-- Quick Replies -->
            @if(isset($quickReplies) && count($quickReplies) > 0 && $messages->isEmpty())
                <div class="px-4 pb-2">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($quickReplies as $reply)
                            <button type="button" class="btn btn-sm btn-outline-light quick-reply-btn" data-message="{{ $reply['value'] }}">
                                {{ $reply['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Input Form -->
            <div class="p-4 border-top border-opacity-10">
                <form method="POST" action="{{ route('customer.assistant.store') }}" class="d-flex gap-2" id="chatForm">
                    @csrf
                    <input name="content" id="chatInput" class="form-control-premium flex-grow-1" placeholder="Nhập tin nhắn của bạn..." required autocomplete="off">
                    <button type="submit" class="btn-premium px-4">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Scroll to bottom of chat
    const container = document.getElementById('chat-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }

    // Quick reply functionality
    document.querySelectorAll('.quick-reply-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const message = this.dataset.message;
            document.getElementById('chatInput').value = message;
            document.getElementById('chatForm').submit();
        });
    });
</script>
@endpush