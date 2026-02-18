<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Requests\Dashboard\StoreCustomerRequest;
use App\Http\Requests\Dashboard\UpdateCustomerRequest;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index()
    {
        $customers = Customer::query()
            ->when(request('search'), function ($query, $search) {
                $query->search($search);
            })
            ->when(request('city'), function ($query, $city) {
                $query->byCity($city);
            })
            ->when(request('status'), function ($query, $status) {
                $query->where('is_active', $status === 'active');
            })
            ->withCount('shipments')
            ->latest()
            ->paginate(15);

        $cities = Customer::distinct()->pluck('city')->filter();

        return view('dashboard.customers.index', compact('customers', 'cities'));
    }

    public function create()
    {
        $title = t('Add New Customer');
        return view('dashboard.customers.create', compact('title'));
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->validated());

        return redirect()->route('dashboard.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['shipments' => function($query) {
            $query->with(['latestEvent', 'branch', 'destinationBranch'])->latest();
        }]);
        
        return view('dashboard.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('dashboard.customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return redirect()->route('dashboard.customers.index')
            ->with('success', t('Customer updated successfully.'));
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('dashboard.customers.index')
            ->with('success', t('Customer deleted successfully.'));
    }

    public function toggleStatus(Customer $customer)
    {
        $customer->update(['is_active' => !$customer->is_active]);

        $status = $customer->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', t("Customer {$status} successfully."));
    }
}
