<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::query()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->q . '%'))
            ->where('is_active', true)
            ->paginate(10)
            ->withQueryString();

        return view('services.index', compact('services'));
    }

    public function show(Service $service)
    {
        abort_unless($service->is_active, 404);

        return view('services.show', compact('service'));
    }
}
