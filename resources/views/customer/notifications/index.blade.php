@extends('layouts.app')

@section('title', 'Thông báo')

@push('styles')
<style>
    .notif-card {
        transition: all 0.2s ease;
        border-left: 4px solid transparent;
    }
    .notif-card:hover {
        transform: translateX(2px);
    }
    .notif-card.unread {
        background: #f0f9ff;
        border-left-color: var(--bs-primary, #0d6efd);
    }
    .notif-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .notif-time {
        font-size: 0.8rem;
        color: #6c757d;
    }
    .badge-unread {
        position: absolute;
        top: -4px;
        right: -4px;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-bell"></i> Thông báo
            @if($unreadCount > 0)
                <span class="badge bg-primary">{{ $unreadCount }} mới</span>
            @endif
        </h2>
        @if($unreadCount > 0)
            <form action="{{ route('customer.notifications.mark-all-read') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-check-all"></i> Đánh dấu tất cả đã đọc
                </button>
            </form>
        @endif
    </div>

    @if($notifications->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-bell-slash display-1 text-muted"></i>
            <p class="text-muted mt-3">Không có thông báo nào</p>
        </div>
    @else
        <div class="list-group">
            @foreach($notifications as $notification)
                <div class="list-group-item notif-card p-3 mb-2 rounded {{ !$notification->is_read ? 'unread' : '' }}"
                     data-notif-id="{{ $notification->id }}">
                    <div class="d-flex align-items-start gap-3">
                        <div class="notif-icon bg-{{ $notification->color ?? 'info' }} bg-opacity-10 text-{{ $notification->color ?? 'info' }}">
                            <i class="bi bi-{{ $notification->icon ?? 'bell' }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1 fw-semibold {{ !$notification->is_read ? 'text-primary' : '' }}">
                                        {{ $notification->title }}
                                        @if(!$notification->is_read)
                                            <span class="badge bg-danger rounded-pill badge-unread">Mới</span>
                                        @endif
                                    </h6>
                                    <p class="mb-1 text-muted small">{{ $notification->content }}</p>
                                    <span class="notif-time">
                                        <i class="bi bi-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if($notification->link)
                                            <li>
                                                <a class="dropdown-item" href="{{ route('customer.notifications.mark-read', $notification) }}">
                                                    <i class="bi bi-eye"></i> Xem chi tiết
                                                </a>
                                            </li>
                                        @endif
                                        <li>
                                            <form action="{{ route('customer.notifications.destroy', $notification) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash"></i> Xóa thông báo
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection