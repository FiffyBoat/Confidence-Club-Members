<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CollectorAssignment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectorAssignmentController extends Controller
{
    public function index(): View
    {
        $assignments = CollectorAssignment::with('collector')
            ->latest()
            ->paginate(20);

        return view('admin.collector-assignments.index', compact('assignments'));
    }

    public function create(): View
    {
        $collectors = User::where('role', 'collector')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.collector-assignments.create', compact('collectors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'collector_id' => ['required', 'exists:users,id'],
            'area_name' => ['required', 'string', 'max:150'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $assignment = CollectorAssignment::create($validated);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'Assigned Collector Area',
            'description' => 'Assigned collector ID '.$assignment->collector_id.' to '.$assignment->area_name,
        ]);

        return redirect()->route('admin.collector-assignments.index')->with('success', 'Collector assignment created.');
    }

    public function destroy(CollectorAssignment $collectorAssignment): RedirectResponse
    {
        $collectorAssignment->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Removed Collector Assignment',
            'description' => 'Removed assignment for collector ID '.$collectorAssignment->collector_id,
        ]);

        return redirect()->route('admin.collector-assignments.index')->with('success', 'Collector assignment removed.');
    }
}
