<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utils\Toast;
use App\Models\Shipment;
use App\Models\Flight;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $canViewAll = $user && ($user->hasRole('super_admin') || $user->hasRole('manager') || $user->can('manage branches'));

        $shipments = Shipment::query();
        $flights = Flight::query();
        $bookings = Booking::query();

        if ($user && method_exists($user,'isBranchManager') && $user->isBranchManager() && !$canViewAll) {
            $shipments->where('branch_id', $user->branch_id);
            $flights->where('branch_id', $user->branch_id);
            $bookings->where('branch_id', $user->branch_id);
        }

        $shipmentsTotal = $shipments->count();
        $flightsTotal = $flights->count();
        $bookingsTotal = $bookings->count();

        return view('dashboard.dashboard.index', compact('shipmentsTotal','flightsTotal','bookingsTotal'));
    }
}
