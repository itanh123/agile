<?php

namespace App\Http\Controllers;

use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->latest()->take(6)->get();

        return view('home', compact('services'));
    }
}
