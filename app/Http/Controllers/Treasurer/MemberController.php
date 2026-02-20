<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\ActivityLog;
use App\Models\Member;
use App\Repositories\MemberRepository;
use App\Services\ReceiptService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function __construct(
        private readonly MemberRepository $members,
        private readonly ReceiptService $receiptService
    )
    {
    }

    public function index(Request $request): View
    {
        $search = $request->input('q');
        $role = $request->user()->role ?? 'viewer';
        $canViewPayments = in_array($role, ['admin', 'treasurer'], true);
        $members = $this->members->paginateWithSearch($search, 15, $canViewPayments);

        return view('members.index', compact('members', 'search', 'canViewPayments'));
    }

    public function create(): View
    {
        return view('members.create');
    }

    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $member = Member::create($request->safe()->only([
            'membership_id',
            'full_name',
            'phone',
            'email',
            'status',
            'join_date',
            'birth_month',
            'birth_day',
        ]));

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'Created Member',
            'description' => 'Created member '.$member->full_name.' ('.$member->membership_id.')',
        ]);

        if ($request->boolean('record_admission_fee')) {
            $contribution = \App\Models\Contribution::create([
                'member_id' => $member->id,
                'type' => 'Admission Fee',
                'description' => 'Membership admission fee',
                'amount' => 200,
                'payment_method' => $request->validated('admission_payment_method'),
                'transaction_date' => $request->validated('admission_transaction_date'),
                'recorded_by' => $request->user()->id,
            ]);

            $contribution->load('member');
            $this->receiptService->createForContribution($contribution, $request->user());
        }

        return redirect()->route('members.index')->with('success', 'Member created successfully.');
    }

    public function show(Member $member): View
    {
        $member->load([
            'contributions' => fn ($query) => $query->with('receipt')->orderBy('transaction_date', 'desc'),
            'loans' => fn ($query) => $query->orderBy('issue_date', 'desc'),
            'loans.repayments' => fn ($query) => $query->orderBy('payment_date', 'desc'),
        ]);

        return view('members.show', compact('member'));
    }

    public function edit(Member $member): View
    {
        return view('members.edit', compact('member'));
    }

    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $member->update($request->validated());

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'Updated Member',
            'description' => 'Updated member '.$member->full_name.' ('.$member->membership_id.')',
        ]);

        return redirect()->route('members.show', $member)->with('success', 'Member updated successfully.');
    }

    public function destroy(Request $request, Member $member): RedirectResponse
    {
        $member->delete();

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'Deleted Member',
            'description' => 'Deleted member '.$member->full_name.' ('.$member->membership_id.')',
        ]);

        return redirect()->route('members.index')->with('success', 'Member deleted.');
    }
}
