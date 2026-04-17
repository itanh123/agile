<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $promotions = $query->latest()->paginate(12);
        
        $activeCount = Promotion::where('is_active', 1)->where('end_at', '>=', now())->count();
        $expiredPromotions = Promotion::where('end_at', '<', now())->count();
        
        // Sum total savings from bookings using this promotion
        $totalSavings = DB::table('bookings')
            ->whereNotNull('promotion_id')
            ->sum('discount_amount');

        return view('admin.promotions.index', compact('promotions', 'activeCount', 'expiredPromotions', 'totalSavings'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
            'is_active' => 'nullable|boolean',
        ], [
            'code.required' => 'Mã voucher không được để trống.',
            'code.unique' => 'Mã voucher này đã tồn tại.',
            'title.required' => 'Tiêu đề không được để trống.',
            'discount_value.required' => 'Giá trị giảm không được để trống.',
            'end_at.required' => 'Ngày kết thúc không được để trống.',
            'end_at.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        Promotion::create($data);

        return redirect()->route('admin.promotions.index')->with('success', 'Đã tạo voucher thành công.');
    }

    public function show(Promotion $promotion)
    {
        $promotion->load(['bookings.user']);

        $usageCount = $promotion->bookings->count();
        $uniqueUsers = $promotion->bookings->pluck('user_id')->unique()->count();
        $totalSaved = $promotion->bookings->sum('discount_amount');

        return view('admin.promotions.show', compact('promotion', 'usageCount', 'uniqueUsers', 'totalSaved'));
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code,' . $promotion->id,
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
            'is_active' => 'nullable|boolean',
        ], [
            'code.required' => 'Mã voucher không được để trống.',
            'code.unique' => 'Mã voucher này đã tồn tại.',
            'title.required' => 'Tiêu đề không được để trống.',
            'discount_value.required' => 'Giá trị giảm không được để trống.',
            'end_at.required' => 'Ngày kết thúc không được để trống.',
            'end_at.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $promotion->update($data);

        return redirect()->route('admin.promotions.index')->with('success', 'Đã cập nhật voucher thành công.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return back()->with('success', 'Promotion deleted.');
    }
}
