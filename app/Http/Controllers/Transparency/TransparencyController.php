<?php

namespace App\Http\Controllers\Transparency;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransparencyController extends Controller
{
    public function index(): View
    {
        $totalMembers = Member::count();
        $totalContributions = Contribution::sum('amount');
        $totalIncome = Income::sum('amount');
        $totalRepayments = LoanRepayment::sum('amount');
        $totalExpenses = Expense::sum('amount');
        $netBalance = ($totalContributions + $totalIncome + $totalRepayments) - $totalExpenses;

        $driver = DB::getDriverName();
        $yearExpr = $driver === 'sqlite' ? "strftime('%Y', transaction_date)" : 'YEAR(transaction_date)';
        $monthExpr = $driver === 'sqlite' ? "strftime('%m', transaction_date)" : 'MONTH(transaction_date)';

        $monthlyContributions = Contribution::selectRaw($yearExpr.' as year, '.$monthExpr.' as month, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get()
            ->reverse();

        $monthlyExpenses = Expense::selectRaw($yearExpr.' as year, '.$monthExpr.' as month, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get()
            ->reverse();

        $expenseBreakdown = Expense::selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $loanSummary = [
            'total_loans' => Loan::count(),
            'total_outstanding' => Loan::sum('balance'),
            'overdue' => Loan::where('balance', '>', 0)->whereDate('due_date', '<', now())->count(),
        ];

        return view('transparency.index', compact(
            'totalMembers',
            'totalContributions',
            'totalIncome',
            'totalRepayments',
            'totalExpenses',
            'netBalance',
            'monthlyContributions',
            'monthlyExpenses',
            'expenseBreakdown',
            'loanSummary'
        ));
    }
}
