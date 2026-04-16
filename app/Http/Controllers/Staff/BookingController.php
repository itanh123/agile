<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\PetProgressImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'pet', 'services'])
            ->where('staff_id', auth()->id());

        if ($request->filled('date')) {
            $query->whereDate('appointment_at', Carbon::parse($request->date));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer')) {
            $searchTerm = $request->customer;
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('fullname', 'like', "%{$searchTerm}%");
            });
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();

        return view('staff.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        abort_unless($booking->staff_id === auth()->id(), 403);
        
        $booking->load(['user', 'pet', 'services', 'images.uploader', 'logs.changedByUser']);

        return view('staff.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        abort_unless($booking->staff_id === auth()->id(), 403);

        $validStatuses = ['confirmed', 'processing', 'completed', 'cancelled'];
        
        $request->validate([
            'status' => 'required|in:' . implode(',', $validStatuses),
            'reason' => 'nullable|string|max:500',
        ], [
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        $oldStatus = $booking->status;
        $newStatus = $request->status;

        $booking->update(['status' => $newStatus]);

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'status' => $newStatus,
            'changed_by' => auth()->id(),
            'note' => $request->reason ?? "Cập nhật trạng thái từ {$oldStatus} sang {$newStatus}",
            'changed_at' => now(),
        ]);

        return back()->with('success', 'Trạng thái booking đã được cập nhật thành công.');
    }

    public function uploadImage(Request $request, Booking $booking)
    {
        abort_unless($booking->staff_id === auth()->id(), 403);

        $request->validate([
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'caption' => 'nullable|string|max:255',
        ], [
            'images.required' => 'Vui lòng chọn ít nhất một hình ảnh.',
            'images.*.image' => 'File phải là hình ảnh.',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'images.*.max' => 'Kích thước hình ảnh không được vượt quá 5MB.',
        ]);

        $uploadedImages = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('pet-progress', 'public');
                
                PetProgressImage::create([
                    'booking_id' => $booking->id,
                    'image_path' => $path,
                    'uploaded_by' => auth()->id(),
                    'caption' => $request->caption ?? 'Hình ảnh tiến độ',
                    'taken_at' => now(),
                ]);
                
                $uploadedImages[] = $path;
            }
        }

        if (count($uploadedImages) > 0) {
            BookingStatusLog::create([
                'booking_id' => $booking->id,
                'status' => $booking->status,
                'changed_by' => auth()->id(),
                'note' => 'Đã tải lên ' . count($uploadedImages) . ' hình ảnh tiến độ.',
                'changed_at' => now(),
            ]);
        }

        return back()->with('success', count($uploadedImages) . ' hình ảnh đã được tải lên thành công.');
    }

    public function addNote(Request $request, Booking $booking)
    {
        abort_unless($booking->staff_id === auth()->id(), 403);

        $request->validate([
            'note' => 'required|string|min:3|max:1000',
            'note_type' => 'nullable|in:condition,mood,health,warning,progress,general',
        ], [
            'note.required' => 'Vui lòng nhập nội dung ghi chú.',
            'note.min' => 'Ghi chú phải có ít nhất 3 ký tự.',
            'note.max' => 'Ghi chú không được vượt quá 1000 ký tự.',
        ]);

        $noteType = $request->note_type ?? 'general';
        $noteTypeLabels = [
            'condition' => 'Tình trạng pet',
            'mood' => 'Tâm trạng',
            'health' => 'Sức khỏe',
            'warning' => 'Cảnh báo',
            'progress' => 'Tiến độ',
            'general' => 'Ghi chú chung',
        ];

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'status' => $booking->status,
            'changed_by' => auth()->id(),
            'note' => "[{$noteTypeLabels[$noteType]}] " . $request->note,
            'changed_at' => now(),
        ]);

        return back()->with('success', 'Ghi chú đã được thêm thành công.');
    }
}
