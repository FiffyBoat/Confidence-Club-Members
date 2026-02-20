<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payer;
use App\Models\Invoice;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class PayerController extends Controller
{
    public function index()
    {
        $payers = Payer::latest()->paginate(15);
        return view('payers.index', compact('payers'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $payers = Payer::where('full_name','like','%'.$query.'%')
            ->orWhere('business_name','like','%'.$query.'%')
            ->orWhere('phone','like','%'.$query.'%')
            ->orWhere('electoral_area','like','%'.$query.'%')
            ->latest()
            ->paginate(15);

        return view('payers.index', compact('payers','query'));
    }

    public function create()
    {
        return view('payers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'payer_type'=>'required|in:individual,business',
            'full_name'=>'nullable|required_if:payer_type,individual|string|max:150',
            'business_name'=>'nullable|required_if:payer_type,business|string|max:150',
            'phone'=>'required|string|max:20',
            'email'=>'nullable|email',
            'location'=>'required|string',
            'electoral_area'=>'required|string'
        ]);

        $payer = Payer::create($request->all());

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Created Payer',
            'description' => 'Created payer '.$payer->full_name.' / '.$payer->business_name
        ]);

        return redirect()->route('payers.index')->with('success','Payer Added');
    }

    public function show(Payer $payer)
    {
        // Show payer history (invoices & payments)
        $invoices = Invoice::where('payer_id',$payer->id)->with('payments')->latest()->get();
        return view('payers.show', compact('payer','invoices'));
    }

    public function edit(Payer $payer)
    {
        return view('payers.edit', compact('payer'));
    }

    public function update(Request $request, Payer $payer)
    {
        $request->validate([
            'payer_type'=>'required|in:individual,business',
            'full_name'=>'nullable|required_if:payer_type,individual|string|max:150',
            'business_name'=>'nullable|required_if:payer_type,business|string|max:150',
            'phone'=>'required|string|max:20',
            'email'=>'nullable|email',
            'location'=>'required|string',
            'electoral_area'=>'required|string'
        ]);

        $payer->update($request->all());

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Updated Payer',
            'description' => 'Updated payer '.$payer->full_name.' / '.$payer->business_name
        ]);

        return redirect()->route('payers.index')->with('success','Payer Updated');
    }

    public function destroy(Payer $payer)
    {
        $payer->delete();

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Deleted Payer',
            'description' => 'Deleted payer '.$payer->full_name.' / '.$payer->business_name
        ]);

        return redirect()->route('payers.index')->with('success','Payer Deleted');
    }
}
