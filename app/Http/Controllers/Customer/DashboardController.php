<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $bookings = Booking::where('user_id', $user->id)->latest()->take(5)->get();

        return view('customer.dashboard', compact('bookings'));
    }
}
