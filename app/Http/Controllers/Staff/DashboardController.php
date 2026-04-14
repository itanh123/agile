<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('staff_id', auth()->id())->latest()->take(10)->get();

        return view('staff.dashboard', compact('bookings'));
    }
}
