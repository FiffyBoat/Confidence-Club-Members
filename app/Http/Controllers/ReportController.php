<?php

namespace App\Http\Controllers;

use App\Exports\RowsExport;
use App\Models\CollectorAssignment;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function dailyRevenue(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $day = Carbon::parse($date);

        $payments = Payment::with(['invoice.revenueType', 'collector'])
            ->whereDate('created_at', $day)
            ->get();

        $total = $payments->sum('amount_paid');
        $byRevenueType = $payments
            ->groupBy(fn ($payment) => $payment->invoice?->revenueType?->name ?? 'Unknown')
            ->map(fn ($group) => $group->sum('amount_paid'))
            ->sortDesc();
        $byMethod = $payments
            ->groupBy('payment_method')
            ->map(fn ($group) => $group->sum('amount_paid'))
            ->sortDesc();

        return view('reports.daily', compact('payments', 'total', 'day', 'byRevenueType', 'byMethod'));
    }

    public function monthlyRevenue(Request $request)
    {
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        [$year, $month] = array_map('intval', explode('-', $selectedMonth));

        $payments = Payment::with(['invoice.revenueType', 'collector'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        $total = $payments->sum('amount_paid');
        $byRevenueType = $payments
            ->groupBy(fn ($payment) => $payment->invoice?->revenueType?->name ?? 'Unknown')
            ->map(fn ($group) => $group->sum('amount_paid'))
            ->sortDesc();

        return view('reports.monthly', compact('payments', 'total', 'selectedMonth', 'byRevenueType'));
    }

    public function outstandingBills(Request $request)
    {
        $asOfDate = Carbon::parse($request->input('as_of', Carbon::today()->toDateString()));

        $invoices = Invoice::with(['payer', 'revenueType', 'payments'])
            ->where('status', '!=', 'paid')
            ->orderBy('due_date')
            ->get();

        $totalOutstanding = $invoices->sum(fn ($invoice) => $invoice->outstandingBalance());
        $overdue = $invoices->filter(fn ($invoice) => Carbon::parse($invoice->due_date)->lt($asOfDate));

        return view('reports.outstanding', compact('invoices', 'totalOutstanding', 'overdue', 'asOfDate'));
    }

    public function collectorPerformance(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $day = Carbon::parse($date);

        $collectorStats = User::where('role', 'collector')
            ->where('is_active', true)
            ->with([
                'payments' => fn ($query) => $query->whereDate('created_at', $day),
                'assignments' => function ($query) use ($day) {
                    $query->whereDate('start_date', '<=', $day)
                        ->where(function ($sub) use ($day) {
                            $sub->whereNull('end_date')->orWhereDate('end_date', '>=', $day);
                        });
                },
            ])
            ->get()
            ->map(function ($collector) use ($day) {
                $assignedAreas = $collector->assignments->pluck('area_name')->filter()->values();
                $actual = $collector->payments->sum('amount_paid');

                $expected = 0;
                if ($assignedAreas->isNotEmpty()) {
                    $expected = Invoice::with(['payer', 'payments'])
                        ->whereIn('status', ['unpaid', 'partial'])
                        ->whereDate('due_date', '<=', $day)
                        ->whereHas('payer', fn ($query) => $query->whereIn('electoral_area', $assignedAreas))
                        ->get()
                        ->sum(fn ($invoice) => $invoice->outstandingBalance());
                }

                return [
                    'collector' => $collector,
                    'assigned_areas' => $assignedAreas,
                    'payments_count' => $collector->payments->count(),
                    'expected' => $expected,
                    'actual' => $actual,
                    'variance' => $actual - $expected,
                ];
            })
            ->sortByDesc('actual')
            ->values();

        $grandTotal = $collectorStats->sum('actual');

        return view('reports.collectors', compact('collectorStats', 'grandTotal', 'day'));
    }

    public function export(string $type, Request $request): Response
    {
        $format = strtolower($request->input('format', 'xlsx'));

        return match ($type) {
            'daily' => $this->exportDaily($request, $format),
            'monthly' => $this->exportMonthly($request, $format),
            'outstanding' => $this->exportOutstanding($request, $format),
            'collectors' => $this->exportCollectors($request, $format),
            default => response('Invalid export type', 400),
        };
    }

    private function exportDaily(Request $request, string $format): Response
    {
        $day = Carbon::parse($request->input('date', Carbon::today()->toDateString()));
        $payments = Payment::with(['invoice', 'collector'])
            ->whereDate('created_at', $day)
            ->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.daily-pdf', compact('payments', 'day'));
            return $pdf->download('daily-revenue-'.$day->format('Y-m-d').'.pdf');
        }

        $rows = [['Date', 'Receipt Number', 'Invoice Number', 'Collector', 'Payment Method', 'Amount']];
        foreach ($payments as $payment) {
            $rows[] = [
                $payment->created_at->format('Y-m-d'),
                $payment->receipt_number,
                $payment->invoice?->invoice_number,
                $payment->collector?->name,
                $payment->payment_method,
                number_format($payment->amount_paid, 2, '.', ''),
            ];
        }

        return Excel::download(new RowsExport($rows), 'daily-revenue-'.$day->format('Y-m-d').'.xlsx');
    }

    private function exportMonthly(Request $request, string $format): Response
    {
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        [$year, $month] = array_map('intval', explode('-', $selectedMonth));
        $payments = Payment::with(['invoice', 'collector'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.monthly-pdf', compact('payments', 'selectedMonth'));
            return $pdf->download('monthly-revenue-'.$selectedMonth.'.pdf');
        }

        $rows = [['Date', 'Receipt Number', 'Invoice Number', 'Collector', 'Payment Method', 'Amount']];
        foreach ($payments as $payment) {
            $rows[] = [
                $payment->created_at->format('Y-m-d'),
                $payment->receipt_number,
                $payment->invoice?->invoice_number,
                $payment->collector?->name,
                $payment->payment_method,
                number_format($payment->amount_paid, 2, '.', ''),
            ];
        }

        return Excel::download(new RowsExport($rows), 'monthly-revenue-'.$selectedMonth.'.xlsx');
    }

    private function exportOutstanding(Request $request, string $format): Response
    {
        $asOfDate = Carbon::parse($request->input('as_of', Carbon::today()->toDateString()));
        $invoices = Invoice::with(['payer', 'revenueType', 'payments'])
            ->where('status', '!=', 'paid')
            ->orderBy('due_date')
            ->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.outstanding-pdf', compact('invoices', 'asOfDate'));
            return $pdf->download('outstanding-bills-'.$asOfDate->format('Y-m-d').'.pdf');
        }

        $rows = [['Invoice Number', 'Payer', 'Revenue Type', 'Amount', 'Paid', 'Outstanding', 'Due Date', 'Overdue']];
        foreach ($invoices as $invoice) {
            $rows[] = [
                $invoice->invoice_number,
                $invoice->payer?->full_name ?? $invoice->payer?->business_name,
                $invoice->revenueType?->name,
                number_format($invoice->amount, 2, '.', ''),
                number_format($invoice->totalPaid(), 2, '.', ''),
                number_format($invoice->outstandingBalance(), 2, '.', ''),
                $invoice->due_date,
                Carbon::parse($invoice->due_date)->lt($asOfDate) ? 'Yes' : 'No',
            ];
        }

        return Excel::download(new RowsExport($rows), 'outstanding-bills-'.$asOfDate->format('Y-m-d').'.xlsx');
    }

    private function exportCollectors(Request $request, string $format): Response
    {
        $day = Carbon::parse($request->input('date', Carbon::today()->toDateString()));

        $collectorStats = User::where('role', 'collector')
            ->where('is_active', true)
            ->with([
                'payments' => fn ($query) => $query->whereDate('created_at', $day),
                'assignments' => function ($query) use ($day) {
                    $query->whereDate('start_date', '<=', $day)
                        ->where(function ($sub) use ($day) {
                            $sub->whereNull('end_date')->orWhereDate('end_date', '>=', $day);
                        });
                },
            ])
            ->get()
            ->map(function ($collector) use ($day) {
                $assignedAreas = $collector->assignments->pluck('area_name')->filter()->values();
                $actual = $collector->payments->sum('amount_paid');
                $expected = 0;
                if ($assignedAreas->isNotEmpty()) {
                    $expected = Invoice::with(['payer', 'payments'])
                        ->whereIn('status', ['unpaid', 'partial'])
                        ->whereDate('due_date', '<=', $day)
                        ->whereHas('payer', fn ($query) => $query->whereIn('electoral_area', $assignedAreas))
                        ->get()
                        ->sum(fn ($invoice) => $invoice->outstandingBalance());
                }

                return [
                    'collector' => $collector,
                    'assigned_areas' => $assignedAreas,
                    'expected' => $expected,
                    'actual' => $actual,
                    'variance' => $actual - $expected,
                ];
            })
            ->values();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.collectors-pdf', compact('collectorStats', 'day'));
            return $pdf->download('collector-performance-'.$day->format('Y-m-d').'.pdf');
        }

        $rows = [['Date', 'Collector', 'Assigned Areas', 'Expected', 'Actual', 'Variance']];
        foreach ($collectorStats as $item) {
            $rows[] = [
                $day->format('Y-m-d'),
                $item['collector']->name,
                $item['assigned_areas']->implode(', '),
                number_format($item['expected'], 2, '.', ''),
                number_format($item['actual'], 2, '.', ''),
                number_format($item['variance'], 2, '.', ''),
            ];
        }

        return Excel::download(new RowsExport($rows), 'collector-performance-'.$day->format('Y-m-d').'.xlsx');
    }
}
