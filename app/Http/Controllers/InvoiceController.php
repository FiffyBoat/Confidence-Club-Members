<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Payer;
use App\Models\RevenueType;
use App\Models\Payment;
use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('payer','revenueType')->latest()->paginate(15);
        return view('invoices.index', compact('invoices'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $invoices = Invoice::where('invoice_number','like','%'.$query.'%')
            ->orWhereHas('payer', function($q) use($query){
                $q->where('full_name','like','%'.$query.'%')
                  ->orWhere('business_name','like','%'.$query.'%');
            })
            ->orWhereHas('revenueType', function($q) use($query){
                $q->where('name','like','%'.$query.'%');
            })
            ->with('payer','revenueType')
            ->latest()
            ->paginate(15);

        return view('invoices.index', compact('invoices','query'));
    }

    public function create()
    {
        $payers = Payer::all();
        $revenueTypes = RevenueType::all();
        return view('invoices.create', compact('payers','revenueTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payer_id' => 'required|exists:payers,id',
            'revenue_type_id' => 'required|exists:revenue_types,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after_or_equal:today'
        ]);

        $invoiceNumber = 'INV-'.date('Y').'-'.str_pad(Invoice::count()+1,4,'0',STR_PAD_LEFT);

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'payer_id' => $request->payer_id,
            'revenue_type_id' => $request->revenue_type_id,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'created_by' => Auth::id(),
            'status' => 'unpaid'
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Created Invoice',
            'description' => 'Invoice '.$invoice->invoice_number.' created for payer '.($invoice->payer->full_name ?? $invoice->payer->business_name)
        ]);

        return redirect()->route('invoices.index')->with('success','Invoice Created');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('payer','revenueType','payments');
        return view('invoices.show', compact('invoice'));
    }

    public function print(Invoice $invoice)
    {
        $invoice->load('payer', 'revenueType', 'payments.collector');
        return view('invoices.print', compact('invoice'));
    }

    public function pdf(Invoice $invoice)
    {
        $invoice->load('payer', 'revenueType', 'payments.collector');

        $pdf = Pdf::loadView('invoices.print', compact('invoice'));

        return $pdf->download('invoice-'.$invoice->invoice_number.'.pdf');
    }

    public function edit(Invoice $invoice)
    {
        $payers = Payer::all();
        $revenueTypes = RevenueType::all();
        return view('invoices.edit', compact('invoice','payers','revenueTypes'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'payer_id' => 'required|exists:payers,id',
            'revenue_type_id' => 'required|exists:revenue_types,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after_or_equal:today'
        ]);

        $invoice->update($request->all());

        // Update status if payments exist
        $totalPaid = $invoice->payments()->sum('amount_paid');
        if($totalPaid >= $invoice->amount){
            $invoice->update(['status'=>'paid']);
        } elseif($totalPaid > 0){
            $invoice->update(['status'=>'partial']);
        } else {
            $invoice->update(['status'=>'unpaid']);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Updated Invoice',
            'description' => 'Invoice '.$invoice->invoice_number.' updated'
        ]);

        return redirect()->route('invoices.index')->with('success','Invoice Updated');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Deleted Invoice',
            'description' => 'Invoice '.$invoice->invoice_number.' deleted'
        ]);

        return redirect()->route('invoices.index')->with('success','Invoice Deleted');
    }
}
