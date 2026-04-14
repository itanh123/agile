<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $revenueByMonth = Booking::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('SUM(total_amount) as total'))
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $statusCounts = Booking::select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->get();
        $mostUsedServices = Service::select('services.name', DB::raw('COUNT(booking_services.id) as total'))
            ->join('booking_services', 'booking_services.service_id', '=', 'services.id')
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact('revenueByMonth', 'statusCounts', 'mostUsedServices'));
    }
}
