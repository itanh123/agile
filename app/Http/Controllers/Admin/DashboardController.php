<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalServices = Service::count();
        $totalRevenue = Booking::where('status', 'completed')->sum('total_amount');
        $totalPromotions = \App\Models\Promotion::where('is_active', 1)->count();
        $recentBookings = Booking::with(['user', 'pet'])->latest()->take(8)->get();
        $statusSummary = Booking::select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->pluck('total', 'status');

        return view('admin.dashboard', compact('totalUsers', 'totalBookings', 'totalServices', 'totalRevenue', 'totalPromotions', 'recentBookings', 'statusSummary'));
    }
}
