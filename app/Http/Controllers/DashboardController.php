<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\Member;
use App\Models\Receipt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalMembers = Member::count();
        $totalContributions = Contribution::sum('amount');
        $totalIncome = Income::sum('amount');
        $totalRepayments = LoanRepayment::sum('amount');
        $totalExpenses = Expense::sum('amount');

        $totalBalance = ($totalContributions + $totalIncome + $totalRepayments) - $totalExpenses;

        $monthlyContributions = Contribution::whereMonth('transaction_date', $today->month)
            ->whereYear('transaction_date', $today->year)
            ->sum('amount');

        $monthlyExpenses = Expense::whereMonth('transaction_date', $today->month)
            ->whereYear('transaction_date', $today->year)
            ->sum('amount');

        $activeLoans = Loan::where('balance', '>', 0)
            ->whereDate('due_date', '>=', $today)
            ->count();
        $overdueLoans = Loan::where('balance', '>', 0)
            ->whereDate('due_date', '<', $today)
            ->count();

        $recentReceipts = Receipt::with('member')
            ->latest()
            ->take(5)
            ->get();

        $ccmSummary = [
            'admission' => Contribution::where('type', 'Admission Fee')->sum('amount'),
            'professor' => Contribution::where('type', 'Professor Donation')->sum('amount'),
            'lawyer' => Contribution::where('type', 'Lawyer Donation')->sum('amount'),
            'extra' => Contribution::where('type', 'like', 'Extra Levies%')->sum('amount'),
            'dues' => Contribution::where('type', 'Monthly Dues')->sum('amount'),
        ];
        $ccmSummary['total'] = $ccmSummary['admission']
            + $ccmSummary['professor']
            + $ccmSummary['lawyer']
            + $ccmSummary['extra']
            + $ccmSummary['dues'];

        return view('dashboard.index', compact(
            'totalMembers',
            'totalBalance',
            'monthlyContributions',
            'monthlyExpenses',
            'activeLoans',
            'overdueLoans',
            'recentReceipts',
            'ccmSummary'
        ));
    }
}
