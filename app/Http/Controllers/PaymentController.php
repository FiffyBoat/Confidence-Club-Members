<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\ActivityLog;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index()
    {
        $query = Payment::with('invoice', 'collector')->latest();

        if (Auth::user()?->role === 'collector') {
            $query->where('collector_id', Auth::id());
        }

        $payments = $query->paginate(15);

        return view('payments.index', compact('payments'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $payments = Payment::where('receipt_number','like','%'.$query.'%')
            ->orWhereHas('invoice', function($q) use($query){
                $q->where('invoice_number','like','%'.$query.'%');
            })
            ->with('invoice','collector')
            ->latest();

        if (Auth::user()?->role === 'collector') {
            $payments->where('collector_id', Auth::id());
        }

        $payments = $payments->paginate(15);

        return view('payments.index', compact('payments','query'));
    }

    public function create()
    {
        $invoices = Invoice::whereIn('status',['unpaid','partial'])->get();
        $collectors = User::where('role', 'collector')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        if (Auth::user()?->role === 'collector') {
            $collectors = $collectors->where('id', Auth::id())->values();
        }

        return view('payments.create', compact('invoices','collectors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,momo,card,bank',
            'collector_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'collector')->where('is_active', true)),
            ],
        ]);

        if (Auth::user()?->role === 'collector') {
            $request->merge(['collector_id' => Auth::id()]);
        }

        $invoice = Invoice::findOrFail($request->invoice_id);

        $receiptNumber = 'REC-'.date('Y').'-'.str_pad(Payment::count()+1,4,'0',STR_PAD_LEFT);

        $payment = Payment::create([
            'receipt_number' => $receiptNumber,
            'invoice_id' => $invoice->id,
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'collector_id' => $request->collector_id,
            'received_by' => Auth::id()
        ]);

        // Update invoice status automatically
        $totalPaid = $invoice->payments()->sum('amount_paid');

        if($totalPaid >= $invoice->amount){
            $invoice->update(['status'=>'paid']);
        } elseif($totalPaid > 0){
            $invoice->update(['status'=>'partial']);
        } else {
            $invoice->update(['status'=>'unpaid']);
        }

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Recorded Payment',
            'description' => 'Payment '.$receiptNumber.' recorded for invoice '.$invoice->invoice_number
        ]);

        return redirect()->route('payments.index')->with('success','Payment Recorded Successfully');
    }

    public function show(Payment $payment)
    {
        $payment->load('invoice','collector');
        return view('payments.show', compact('payment'));
    }

    public function print(Payment $payment)
    {
        $payment->load('invoice', 'collector');
        return view('payments.print', compact('payment'));
    }

    public function pdf(Payment $payment)
    {
        $payment->load('invoice', 'collector');
        $pdf = Pdf::loadView('payments.print', compact('payment'));

        return $pdf->download('receipt-'.$payment->receipt_number.'.pdf');
    }

    public function destroy(Payment $payment)
    {
        $invoice = $payment->invoice;

        $payment->delete();

        // Recalculate invoice status after deletion
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
            'action' => 'Deleted Payment',
            'description' => 'Payment deleted for invoice '.$invoice->invoice_number
        ]);

        return redirect()->route('payments.index')->with('success','Payment Deleted');
    }
}
