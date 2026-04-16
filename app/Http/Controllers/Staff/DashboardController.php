<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $staffId = auth()->id();
        $today = Carbon::today();

        $totalBookings = Booking::where('staff_id', $staffId)->count();
        $processingBookings = Booking::where('staff_id', $staffId)
            ->where('status', 'processing')
            ->count();
        $completedToday = Booking::where('staff_id', $staffId)
            ->where('status', 'completed')
            ->whereDate('appointment_at', $today)
            ->count();
        $cancelledBookings = Booking::where('staff_id', $staffId)
            ->where('status', 'cancelled')
            ->count();
        $pendingBookings = Booking::where('staff_id', $staffId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $todayBookings = Booking::with(['user', 'pet'])
            ->where('staff_id', $staffId)
            ->whereDate('appointment_at', $today)
            ->orderBy('appointment_at')
            ->get();

        $recentBookings = Booking::with(['user', 'pet'])
            ->where('staff_id', $staffId)
            ->latest()
            ->take(5)
            ->get();

        return view('staff.dashboard', compact(
            'totalBookings',
            'processingBookings',
            'completedToday',
            'cancelledBookings',
            'pendingBookings',
            'todayBookings',
            'recentBookings'
        ));
    }

    public function team()
    {
        $currentUser = auth()->user();
        $teamMembers = User::with('role')
            ->where('manager_id', $currentUser->id)
            ->get();

        $teamIds = $teamMembers->pluck('id')->toArray();
        $teamIds[] = $currentUser->id;

        $teamBookings = Booking::with(['user', 'pet', 'staff'])
            ->whereIn('staff_id', $teamIds)
            ->latest()
            ->take(20)
            ->get();

        return view('staff.team', compact('teamMembers', 'teamBookings', 'currentUser'));
    }
}
