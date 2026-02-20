@extends('layouts.app')

@section('content')
@php
    $role = auth()->user()->role ?? 'viewer';
    $isViewer = $role === 'viewer';
    $isTreasurer = $role === 'treasurer';
    $isAdmin = $role === 'admin';
@endphp

<div class="page-hero mb-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
        <div>
            <div class="hero-badge">User Manual</div>
            <h2 class="page-title mb-1">{{ config('app.name') }} Guide</h2>
            <div class="page-subtitle">
                @if($isViewer)
                    Read-only guidance tailored to what viewers can access.
                @elseif($isTreasurer)
                    Treasurer-only guidance for recording payments, receipts, and reports.
                @else
                    Everything you need to manage members, payments, receipts, and reports.
                @endif
            </div>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <span class="pill"><i class="bi bi-shield-check"></i>Role-based access</span>
                <span class="pill"><i class="bi bi-receipt"></i>Receipts for all payments</span>
                <span class="pill"><i class="bi bi-bar-chart-line"></i>Printable reports</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('help.pdf') }}" class="btn btn-outline-primary"><i class="bi bi-file-earmark-pdf me-1"></i>Open PDF</a>
            <div class="hero-icon d-none d-lg-flex"><i class="bi bi-book"></i></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
                <strong>Roles & Access</strong>
            </div>
            <div class="card-body">
                @if($isViewer)
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-success p-3"><i class="bi bi-eye"></i></span>
                        <div>
                            <div class="fw-semibold">Viewer</div>
                            <div class="text-muted small">Read-only access to dashboard, members list, reports, and birthdays.</div>
                        </div>
                    </div>
                @elseif($isTreasurer)
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-primary p-3"><i class="bi bi-wallet2"></i></span>
                        <div>
                            <div class="fw-semibold">Treasurer</div>
                            <div class="text-muted small">Records financial activity, generates receipts, and runs reports.</div>
                        </div>
                    </div>
                @else
                    <div class="d-flex gap-3 mb-3">
                        <span class="badge text-bg-secondary p-3"><i class="bi bi-shield-lock"></i></span>
                        <div>
                            <div class="fw-semibold">Admin</div>
                            <div class="text-muted small">Full access to all modules, users, and activity logs.</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mb-3">
                        <span class="badge text-bg-primary p-3"><i class="bi bi-wallet2"></i></span>
                        <div>
                            <div class="fw-semibold">Treasurer</div>
                            <div class="text-muted small">Records financial activity, generates receipts, and runs reports.</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-success p-3"><i class="bi bi-eye"></i></span>
                        <div>
                            <div class="fw-semibold">Viewer</div>
                            <div class="text-muted small">Read-only access to dashboards, reports, and birthdays.</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
                <strong>{{ $isViewer ? 'Viewer Access' : ($isTreasurer ? 'Treasurer Modules' : 'Core Modules') }}</strong>
            </div>
            <div class="card-body">
                @if($isViewer)
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Dashboard</div>
                                <div class="stat-value">Read-only metrics</div>
                                <div class="text-muted small mt-2">See totals and key performance summaries.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Members</div>
                                <div class="stat-value">Directory view</div>
                                <div class="text-muted small mt-2">View member list without editing or recording.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Reports</div>
                                <div class="stat-value">View/Export</div>
                                <div class="text-muted small mt-2">Open reports and download summaries.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Birthdays</div>
                                <div class="stat-value">Upcoming view</div>
                                <div class="text-muted small mt-2">Track birthdays and celebrations.</div>
                            </div>
                        </div>
                    </div>
                @elseif($isTreasurer)
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Members</div>
                                <div class="stat-value">Register & update</div>
                                <div class="text-muted small mt-2">Create members and record admission fees.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Monthly Dues</div>
                                <div class="stat-value">GHS 50 / month</div>
                                <div class="text-muted small mt-2">Record dues and track arrears.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Contributions</div>
                                <div class="stat-value">All member payments</div>
                                <div class="text-muted small mt-2">Record admissions, dues, and special contributions.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Special Contributions</div>
                                <div class="stat-value">Purpose-driven</div>
                                <div class="text-muted small mt-2">Record special contributions and pooled donations.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Income & Expenses</div>
                                <div class="stat-value">Group finance</div>
                                <div class="text-muted small mt-2">Record operational inflows and outflows.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Loans</div>
                                <div class="stat-value">Issue & repay</div>
                                <div class="text-muted small mt-2">Track balances and generate receipts.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Receipts</div>
                                <div class="stat-value">View / Print</div>
                                <div class="text-muted small mt-2">Access PDF receipts for every payment.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Reports</div>
                                <div class="stat-value">PDF + CSV</div>
                                <div class="text-muted small mt-2">Run summary and detailed financial reports.</div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Members</div>
                                <div class="stat-value">Profiles & admission fee</div>
                                <div class="text-muted small mt-2">Register members and optionally record GHS 200 admission fee.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Monthly Dues</div>
                                <div class="stat-value">GHS 50 / month</div>
                                <div class="text-muted small mt-2">Track paid months, arrears, and expected balances.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Contributions</div>
                                <div class="stat-value">All member payments</div>
                                <div class="text-muted small mt-2">Record admission, dues, and special contributions.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Loans</div>
                                <div class="stat-value">Issue & repayment</div>
                                <div class="text-muted small mt-2">Track balances and generate receipts for repayments.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Income & Expenses</div>
                                <div class="stat-value">Group finance</div>
                                <div class="text-muted small mt-2">Record operational inflows and outflows.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-card h-100">
                                <div class="stat-label">Receipts & Reports</div>
                                <div class="stat-value">PDF + CSV</div>
                                <div class="text-muted small mt-2">View, print, and download receipts and reports.</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(! $isViewer)
<div class="row g-3 mt-1">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
                <strong>Business Rules</strong>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Monthly dues are GHS 50 per member.</li>
                    <li>Admission fee is GHS 200 at member registration.</li>
                    <li>Special contributions must be at least GHS 200 and require a purpose.</li>
                    <li>Every payment automatically generates a receipt.</li>
                    <li>Transparency portal is read-only and hides individual member data.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
                <strong>Receipts</strong>
            </div>
            <div class="card-body">
                <div class="text-muted mb-2">Receipts are created for:</div>
                <ul class="mb-0">
                    <li>Admission fees</li>
                    <li>Monthly dues</li>
                    <li>Contributions and special contributions</li>
                    <li>Income records</li>
                    <li>Loan repayments</li>
                </ul>
                <div class="text-muted small mt-3">Receipts include club name, logo, payment type, and a PAID watermark.</div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-white">
        <strong>{{ $isViewer ? 'Viewer Tasks' : ($isTreasurer ? 'Treasurer Tasks' : 'Common Tasks') }}</strong>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @if($isViewer)
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-secondary p-3"><i class="bi bi-speedometer2"></i></span>
                        <div>
                            <div class="fw-semibold">Review dashboard</div>
                            <div class="text-muted small">Dashboard → Review totals and summaries.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-primary p-3"><i class="bi bi-people"></i></span>
                        <div>
                            <div class="fw-semibold">Browse members</div>
                            <div class="text-muted small">Members → View directory and profiles.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-danger p-3"><i class="bi bi-file-earmark-text"></i></span>
                        <div>
                            <div class="fw-semibold">Open reports</div>
                            <div class="text-muted small">Reports → View or export summaries.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-secondary p-3"><i class="bi bi-balloon"></i></span>
                        <div>
                            <div class="fw-semibold">Check birthdays</div>
                            <div class="text-muted small">Birthdays → See today and upcoming celebrations.</div>
                        </div>
                    </div>
                </div>
            @elseif($isTreasurer)
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-secondary p-3"><i class="bi bi-person-plus"></i></span>
                        <div>
                            <div class="fw-semibold">Register a member</div>
                            <div class="text-muted small">Members → Add Member → Save. Tick admission fee if paid.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-primary p-3"><i class="bi bi-calendar-check"></i></span>
                        <div>
                            <div class="fw-semibold">Record monthly dues</div>
                            <div class="text-muted small">Dues → Select member + month → Save.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-success p-3"><i class="bi bi-stars"></i></span>
                        <div>
                            <div class="fw-semibold">Record special contribution</div>
                            <div class="text-muted small">Special Contributions → Add amount + purpose → Save.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-warning p-3"><i class="bi bi-receipt"></i></span>
                        <div>
                            <div class="fw-semibold">Print a receipt</div>
                            <div class="text-muted small">Receipts → View / Print → Download if needed.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-danger p-3"><i class="bi bi-file-earmark-text"></i></span>
                        <div>
                            <div class="fw-semibold">Run reports</div>
                            <div class="text-muted small">Reports → Export PDF/CSV or open detailed report.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-secondary p-3"><i class="bi bi-bank"></i></span>
                        <div>
                            <div class="fw-semibold">Record loan repayment</div>
                            <div class="text-muted small">Loans → Open loan → Record repayment.</div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-secondary p-3"><i class="bi bi-person-plus"></i></span>
                        <div>
                            <div class="fw-semibold">Register a member</div>
                            <div class="text-muted small">Members → Add Member → Save. Tick admission fee if paid.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-primary p-3"><i class="bi bi-calendar-check"></i></span>
                        <div>
                            <div class="fw-semibold">Record monthly dues</div>
                            <div class="text-muted small">Dues → Select member + month → Save.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-success p-3"><i class="bi bi-stars"></i></span>
                        <div>
                            <div class="fw-semibold">Record special contribution</div>
                            <div class="text-muted small">Special Contributions → Add amount + purpose → Save.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-warning p-3"><i class="bi bi-receipt"></i></span>
                        <div>
                            <div class="fw-semibold">Print a receipt</div>
                            <div class="text-muted small">Receipts → View / Print → Download if needed.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-danger p-3"><i class="bi bi-file-earmark-text"></i></span>
                        <div>
                            <div class="fw-semibold">Run reports</div>
                            <div class="text-muted small">Reports → Export PDF/CSV or open detailed report.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <span class="badge text-bg-secondary p-3"><i class="bi bi-balloon"></i></span>
                        <div>
                            <div class="fw-semibold">View birthdays</div>
                            <div class="text-muted small">Birthdays → See today and upcoming celebrations.</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
