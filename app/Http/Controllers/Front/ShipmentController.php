<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.shipments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Redirect to dashboard route for proper permission handling
        return redirect()->route('dashboard.shipments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment)
    {
        return view('dashboard.shipments.show', compact('shipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment)
    {
        return view('dashboard.shipments.create', compact('shipment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shipment $shipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment)
    {
        //
    }

    /**
     * Public shipment tracking page
     */
    public function track(Request $request)
    {
        $trackingNumber = $request->get('tracking_number');
        $shipment = null;
        $error = null;

        if ($trackingNumber) {
            $shipment = Shipment::tracking($trackingNumber)->first();

            if (!$shipment) {
                $error = 'not_found';
            }
        }

        return view('front.shipment.track', compact('shipment', 'trackingNumber', 'error'));
    }
}
