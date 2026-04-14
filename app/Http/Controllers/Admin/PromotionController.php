<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()->paginate(12);

        return view('admin.promotions.index', compact('promotions'));
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
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
            'is_active' => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        Promotion::create($data);

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion created.');
    }

    public function show(Promotion $promotion)
    {
        return view('admin.promotions.show', compact('promotion'));
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
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
            'is_active' => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $promotion->update($data);

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion updated.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return back()->with('success', 'Promotion deleted.');
    }
}
