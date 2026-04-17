<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::with('bookings')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->q . '%'))
            ->when($request->filled('type'), fn ($q) => $q->where('service_type', $request->type))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->status))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'service_type' => 'required|in:grooming,vaccination,spa,checkup,surgery',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        Service::create($data);

        return redirect()->route('admin.services.index')->with('success', 'Service created.');
    }

    public function show(Service $service)
    {
        $service->load(['bookings.user', 'bookings.pet']);

        $totalBookings = $service->bookings->count();
        $totalRevenue = $service->bookings->where('status', 'completed')->sum('price');
        $thisMonthBookings = $service->bookings()->whereMonth('bookings.created_at', now()->month)->whereYear('bookings.created_at', now()->year)->count();

        return view('admin.services.show', compact('service', 'totalBookings', 'totalRevenue', 'thisMonthBookings'));
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'service_type' => 'required|in:grooming,vaccination,spa,checkup,surgery',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $service->update($data);

        return redirect()->route('admin.services.index')->with('success', 'Service updated.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return back()->with('success', 'Service deleted.');
    }
}
