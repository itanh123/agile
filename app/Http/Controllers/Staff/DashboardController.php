<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $staffId = auth()->id();
        $bookings = Booking::where('staff_id', $staffId)->latest()->take(10)->get();
        $totalBookings = Booking::where('staff_id', $staffId)->count();
        $processingBookings = Booking::where('staff_id', $staffId)->where('status', 'processing')->count();
        $activeBookings = Booking::where('staff_id', $staffId)->whereIn('status', ['confirmed', 'completed'])->count();

        return view('staff.dashboard', compact('bookings', 'totalBookings', 'processingBookings', 'activeBookings'));
    }
}
