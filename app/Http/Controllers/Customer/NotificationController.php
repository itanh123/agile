<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::where('user_id', auth()->id());

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->latest()->paginate(20);

        $unreadCount = Notification::where('user_id', auth()->id())->where('is_read', false)->count();

        return view('customer.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(Notification $notification)
    {
        abort_unless($notification->user_id === auth()->id(), 403);
        $notification->markAsRead();

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back();
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc.');
    }

    public function destroy(Notification $notification)
    {
        abort_unless($notification->user_id === auth()->id(), 403);
        $notification->delete();

        return back()->with('success', 'Đã xóa thông báo.');
    }
}