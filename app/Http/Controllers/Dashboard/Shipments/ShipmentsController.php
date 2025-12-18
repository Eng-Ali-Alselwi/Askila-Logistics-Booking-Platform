<?php

namespace App\Http\Controllers\Dashboard\Shipments;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Actions\Shipments\RecordShipmentEvent;
use App\Enums\ShipmentStatus;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\Auth;

class ShipmentsController extends Controller
{
    public function index()
    {
        return view('dashboard.shipments.index');
    }

    public function create()
    {
        // Check if user has permission to create shipments
        if (!PermissionHelper::canCreate('shipments')) {
            abort(403, 'Unauthorized action.');
        }
        
        $shipment = null;
        return view('dashboard.shipments.create' , compact('shipment'));
    }

    public function store(Request $request)
    {
        // Check if user has permission to create shipments
        if (!PermissionHelper::canCreate('shipments')) {
            abort(403, 'Unauthorized action.');
        }
        
        // This will be handled by Livewire component
        return redirect()->route('dashboard.shipments.index');
    }

    public function show(Shipment $shipment)
    {
        // Check if user has permission to view shipments
        if (!PermissionHelper::canView('shipments')) {
            abort(403, 'Unauthorized action.');
        }
        // Branch restriction: Branch Managers can only access their branch shipments
        $user = Auth::user();
        if ($user && method_exists($user, 'isBranchManager') && $user->isBranchManager()) {
            if (!is_null($shipment->branch_id) && $shipment->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized: shipment not in your branch.');
            }
        }
        
        $shipment->load(['events.creator', 'creator']);
        return view('dashboard.shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        // Check if user has permission to edit shipments
        if (!PermissionHelper::canEdit('shipments')) {
            abort(403, 'Unauthorized action.');
        }
        // Branch restriction
        $user = Auth::user();
        if ($user && method_exists($user, 'isBranchManager') && $user->isBranchManager()) {
            if (!is_null($shipment->branch_id) && $shipment->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized: shipment not in your branch.');
            }
        }
        
        return view('dashboard.shipments.edit', compact('shipment'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        // Check if user has permission to edit shipments
        if (!PermissionHelper::canEdit('shipments')) {
            abort(403, 'Unauthorized action.');
        }
        // Branch restriction
        $user = Auth::user();
        if ($user && method_exists($user, 'isBranchManager') && $user->isBranchManager()) {
            if (!is_null($shipment->branch_id) && $shipment->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized: shipment not in your branch.');
            }
        }
        
        // This will be handled by Livewire component
        return redirect()->route('dashboard.shipments.index');
    }

    public function destroy(Shipment $shipment)
    {
        // Check if user has permission to delete shipments
        if (!PermissionHelper::canDelete('shipments')) {
            abort(403, 'Unauthorized action.');
        }
        // Branch restriction
        $user = Auth::user();
        if ($user && method_exists($user, 'isBranchManager') && $user->isBranchManager()) {
            if (!is_null($shipment->branch_id) && $shipment->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized: shipment not in your branch.');
            }
        }
        
        $shipment->delete();
        return redirect()->route('dashboard.shipments.index')
            ->with('success', 'Shipment deleted successfully.');
    }

    public function updateStatus(Request $request, Shipment $shipment, RecordShipmentEvent $recordEvent)
    {
        // Check if user has permission to update shipment status
        if (!PermissionHelper::hasPermission('update shipment status')) {
            abort(403, 'Unauthorized action.');
        }
        // Branch restriction
        $user = Auth::user();
        if ($user && method_exists($user, 'isBranchManager') && $user->isBranchManager()) {
            if (!is_null($shipment->branch_id) && $shipment->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized: shipment not in your branch.');
            }
        }
        
        $request->validate([
            'status' => 'required|string|in:' . implode(',', array_map(fn($case) => $case->value, ShipmentStatus::cases())),
            'location_text' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'happened_at' => 'nullable|date',
        ]);

        $recordEvent->handle($shipment, $request->status, [
            'location_text' => $request->location_text,
            'notes' => $request->notes,
            'happened_at' => $request->happened_at ?? now(),
        ]);

        return back()->with('success', 'Shipment status updated successfully.');
    }

    public function export(Request $request)
    {
        // Check if user has permission to export shipments
        if (!PermissionHelper::canExport('shipments')) {
            abort(403, 'Unauthorized action.');
        }
        
        $shipments = Shipment::query()
            ->with(['latestEvent', 'creator'])
            ->when($request->status, fn($q) => $q->where('current_status', $request->status))
            ->when($request->from && $request->to, function($q) use ($request) {
                $q->whereBetween('created_at', [$request->from, $request->to]);
            })
            ->latest()
            ->get();

        // Here you would implement CSV/Excel export
        // For now, return JSON
        return response()->json($shipments);
    }
}