<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Http\Requests\Dashboard\StoreBranchRequest;
use App\Http\Requests\Dashboard\UpdateBranchRequest;
use Illuminate\Http\Request;

class BranchesController extends Controller
{
    public function index()
    {
        $branches = Branch::query()
            ->when(request('search'), function ($query, $search) {
                $query->search($search);
            })
            ->when(request('city'), function ($query, $city) {
                $query->byCity($city);
            })
            ->when(request('status'), function ($query, $status) {
                $query->where('is_active', $status === 'active');
            })
            ->withCount(['users', 'shipments'])
            ->latest()
            ->paginate(15);

        $cities = Branch::distinct()->pluck('city')->filter();

        return view('dashboard.branches.index', compact('branches', 'cities'));
    }

    public function create()
    {
        $title = t('Add New Branch');
        return view('dashboard.branches.create', compact('title'));
    }

    public function store(StoreBranchRequest $request)
    {
        $branch = Branch::create($request->validated());

        return redirect()->route('dashboard.branches.index')
            ->with('success', t('Branch created successfully.'));
    }

    public function show(Branch $branch)
    {
        $branch->load(['users', 'shipments' => function($query) {
            $query->with(['latestEvent', 'customer'])->latest()->limit(10);
        }]);
        
        return view('dashboard.branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        return view('dashboard.branches.edit', compact('branch'));
    }

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $branch->update($request->validated());

        return redirect()->route('dashboard.branches.index')
            ->with('success', t('Branch updated successfully.'));
    }

    public function destroy(Branch $branch)
    {
        if ($branch->users()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete branch with assigned users.']);
        }

        $branch->delete();

        return redirect()->route('dashboard.branches.index')
            ->with('success', t('Branch deleted successfully.'));
    }

    public function toggleStatus(Branch $branch)
    {
        $branch->update(['is_active' => !$branch->is_active]);

        $status = $branch->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', t("Branch {$status} successfully."));
    }
}
