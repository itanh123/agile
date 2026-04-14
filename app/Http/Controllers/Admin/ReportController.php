<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('status', 'completed')->sum('total_amount');
        $totalCustomers = User::whereHas('role', fn($q) => $q->where('slug', 'customer'))->count();
        $avgBookingValue = $totalBookings > 0 ? $totalRevenue / $totalBookings : 0;

        $revenueByMonth = Booking::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('SUM(total_amount) as total'), DB::raw('COUNT(*) as bookings'))
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $statusCounts = Booking::select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->get();
        $mostUsedServices = Service::select('services.name', 'services.service_type', DB::raw('COUNT(booking_services.id) as total'))
            ->join('booking_services', 'booking_services.service_id', '=', 'services.id')
            ->groupBy('services.id', 'services.name', 'services.service_type')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $topCustomers = User::withCount('bookings')
            ->withSum('bookings as total_spent', fn($q) => $q->where('status', 'completed')->select(DB::raw('SUM(total_amount)')))
            ->whereHas('bookings')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact(
            'totalBookings', 
            'totalRevenue', 
            'totalCustomers', 
            'avgBookingValue',
            'revenueByMonth',
            'statusCounts', 
            'mostUsedServices',
            'topCustomers'
        ));
    }
}
