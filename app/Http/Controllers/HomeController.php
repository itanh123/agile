<?php

namespace App\Http\Controllers;

use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->latest()->take(6)->get();
        $promotions = \App\Models\Promotion::where('is_active', true)
            ->where('end_at', '>=', now())
            ->take(3)
            ->get();

        return view('home', compact('services', 'promotions'));
    }
}
