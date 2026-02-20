<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RevenueType;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class RevenueTypeController extends Controller
{
    public function index()
    {
        $types = RevenueType::latest()->paginate(15);
        return view('revenue_types.index', compact('types'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $types = RevenueType::where('name','like','%'.$query.'%')
            ->orWhere('category','like','%'.$query.'%')
            ->latest()
            ->paginate(15);

        return view('revenue_types.index', compact('types','query'));
    }

    public function create()
    {
        return view('revenue_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:150',
            'category'=>'required|string|max:100',
            'default_amount'=>'required|numeric|min:0',
            'frequency'=>'required|in:one-time,monthly,yearly'
        ]);

        $type = RevenueType::create($request->all());

        // Log activity
        ActivityLog::create([
            'user_id'=>Auth::id(),
            'action'=>'Created Revenue Type',
            'description'=>'Created revenue type '.$type->name
        ]);

        return redirect()->route('revenue-types.index')->with('success','Revenue Type Added');
    }

    public function edit(RevenueType $revenueType)
    {
        return view('revenue_types.edit', compact('revenueType'));
    }

    public function update(Request $request, RevenueType $revenueType)
    {
        $request->validate([
            'name'=>'required|string|max:150',
            'category'=>'required|string|max:100',
            'default_amount'=>'required|numeric|min:0',
            'frequency'=>'required|in:one-time,monthly,yearly'
        ]);

        $revenueType->update($request->all());

        ActivityLog::create([
            'user_id'=>Auth::id(),
            'action'=>'Updated Revenue Type',
            'description'=>'Updated revenue type '.$revenueType->name
        ]);

        return redirect()->route('revenue-types.index')->with('success','Revenue Type Updated');
    }

    public function destroy(RevenueType $revenueType)
    {
        $revenueType->delete();

        ActivityLog::create([
            'user_id'=>Auth::id(),
            'action'=>'Deleted Revenue Type',
            'description'=>'Deleted revenue type '.$revenueType->name
        ]);

        return redirect()->route('revenue-types.index')->with('success','Revenue Type Deleted');
    }
}
