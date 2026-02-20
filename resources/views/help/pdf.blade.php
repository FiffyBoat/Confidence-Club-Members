<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }} User Manual</title>
    <style>
        @page { margin: 26px 32px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #1d1d1d; font-size: 12px; }
        h1 { font-size: 20px; margin: 0 0 8px; }
        h2 { font-size: 14px; margin: 18px 0 8px; }
        h3 { font-size: 12px; margin: 12px 0 6px; }
        .subtitle { color: #6b6b6b; margin-bottom: 16px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; background: #fff2f5; color: #8f0019; font-size: 10px; letter-spacing: 0.05em; text-transform: uppercase; }
        .section { margin-bottom: 12px; }
        ul { margin: 6px 0 0 16px; }
        li { margin-bottom: 4px; }
        .muted { color: #6b6b6b; }
        .box {
            border: 1px solid #ead7dc;
            border-radius: 8px;
            padding: 10px 12px;
            margin-top: 8px;
            background: #fff9fb;
        }
        .grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .grid td {
            vertical-align: top;
            padding: 6px 8px;
            border-bottom: 1px solid #f0e0e4;
        }
        .grid td strong { display: block; margin-bottom: 2px; }
    </style>
</head>
<body>
    @php
        $role = auth()->user()->role ?? 'viewer';
        $isViewer = $role === 'viewer';
        $isTreasurer = $role === 'treasurer';
        $isAdmin = $role === 'admin';
    @endphp

    <div class="badge">User Manual</div>
    <h1>{{ config('app.name') }} Guide</h1>
    <div class="subtitle">
        @if($isViewer)
            Read-only guidance tailored to what viewers can access.
        @elseif($isTreasurer)
            Treasurer-only guidance for recording payments, receipts, and reports.
        @else
            Roles, modules, and daily workflows for the Confidence Club Members system.
        @endif
    </div>

    <div class="section">
        <h2>Roles & Access</h2>
        <div class="box">
            <ul>
                @if($isViewer)
                    <li><strong>Viewer:</strong> Read-only access to dashboard, members list, reports, birthdays, and transparency.</li>
                @elseif($isTreasurer)
                    <li><strong>Treasurer:</strong> Records financial activity, generates receipts, and runs reports.</li>
                @else
                    <li><strong>Admin:</strong> Full access to all modules, user management, and activity logs.</li>
                    <li><strong>Treasurer:</strong> Records financial activity, generates receipts, and runs reports.</li>
                    <li><strong>Viewer:</strong> Read-only access to dashboard, reports, birthdays, and transparency.</li>
                @endif
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>{{ $isViewer ? 'Viewer Access' : ($isTreasurer ? 'Treasurer Modules' : 'Core Modules') }}</h2>
        @if($isViewer)
            <table class="grid">
                <tr>
                    <td><strong>Dashboard</strong>Read-only totals and summaries.</td>
                    <td><strong>Members</strong>View the member directory and profiles.</td>
                </tr>
                <tr>
                    <td><strong>Reports</strong>Open and export summary reports.</td>
                    <td><strong>Birthdays</strong>See today and upcoming celebrations.</td>
                </tr>
                <tr>
                    <td><strong>Transparency</strong>Public read-only totals.</td>
                    <td><strong>Role Guide</strong>Understand permissions and limits.</td>
                </tr>
            </table>
        @elseif($isTreasurer)
            <table class="grid">
                <tr>
                    <td><strong>Members</strong>Register profiles and record admission fees.</td>
                    <td><strong>Monthly Dues</strong>Record and track GHS 50 per month per member.</td>
                </tr>
                <tr>
                    <td><strong>Contributions</strong>Record admission, dues, and special contributions.</td>
                    <td><strong>Special Contributions</strong>Purpose-driven payments (min GHS 200).</td>
                </tr>
                <tr>
                    <td><strong>Donations</strong>Track donations pulled from contribution pools.</td>
                    <td><strong>Income & Expenses</strong>Record operational inflows and outflows.</td>
                </tr>
                <tr>
                    <td><strong>Loans</strong>Issue loans, track balances, record repayments.</td>
                    <td><strong>Receipts & Reports</strong>View, print, and export PDFs/CSVs.</td>
                </tr>
            </table>
        @else
            <table class="grid">
                <tr>
                    <td><strong>Members</strong>Register profiles and optionally record admission fee (GHS 200).</td>
                    <td><strong>Monthly Dues</strong>Record and track GHS 50 per month per member.</td>
                </tr>
                <tr>
                    <td><strong>Contributions</strong>Record admission, dues, and special contributions.</td>
                    <td><strong>Special Contributions</strong>Purpose-driven payments (min GHS 200).</td>
                </tr>
                <tr>
                    <td><strong>Donations</strong>Track donations pulled from contribution pools.</td>
                    <td><strong>Income & Expenses</strong>Record operational inflows and outflows.</td>
                </tr>
                <tr>
                    <td><strong>Loans</strong>Issue loans, track balances, record repayments.</td>
                    <td><strong>Receipts & Reports</strong>View, print, and export PDFs/CSVs.</td>
                </tr>
            </table>
        @endif
    </div>

    @if(! $isViewer)
    <div class="section">
        <h2>Business Rules</h2>
        <ul>
            <li>Monthly dues are GHS 50 per member.</li>
            <li>Admission fee is GHS 200 at member registration.</li>
            <li>Special contributions require a purpose and must be at least GHS 200.</li>
            <li>Every payment generates a receipt automatically.</li>
            <li>Transparency portal is read-only and hides individual member data.</li>
        </ul>
    </div>

    <div class="section">
        <h2>Receipts</h2>
        <div class="box">
            <div class="muted">Receipts are created for:</div>
            <ul>
                <li>Admission fees</li>
                <li>Monthly dues</li>
                <li>Contributions and special contributions</li>
                <li>Income records</li>
                <li>Loan repayments</li>
            </ul>
        </div>
    </div>
    @endif

    <div class="section">
        <h2>{{ $isViewer ? 'Viewer Tasks' : ($isTreasurer ? 'Treasurer Tasks' : 'Common Tasks') }}</h2>
        @if($isViewer)
            <h3>Review dashboard</h3>
            <div class="muted">Dashboard → Review totals and summaries.</div>

            <h3>Browse members</h3>
            <div class="muted">Members → View directory and profiles.</div>

            <h3>Open reports</h3>
            <div class="muted">Reports → View or export summaries.</div>

            <h3>Check birthdays</h3>
            <div class="muted">Birthdays → See today and upcoming celebrations.</div>
        @elseif($isTreasurer)
            <h3>Register a member</h3>
            <div class="muted">Members → Add Member → Save. Tick admission fee if paid.</div>

            <h3>Record monthly dues</h3>
            <div class="muted">Dues → Select member + month → Save.</div>

            <h3>Record special contribution</h3>
            <div class="muted">Special Contributions → Add amount + purpose → Save.</div>

            <h3>Print a receipt</h3>
            <div class="muted">Receipts → View/Print → Download if needed.</div>

            <h3>Run reports</h3>
            <div class="muted">Reports → Export PDF/CSV or open detailed report.</div>

            <h3>Record loan repayment</h3>
            <div class="muted">Loans → Open loan → Record repayment.</div>
        @else
            <h3>Register a member</h3>
            <div class="muted">Members → Add Member → Save. Tick admission fee if paid.</div>

            <h3>Record monthly dues</h3>
            <div class="muted">Dues → Select member + month → Save.</div>

            <h3>Record special contribution</h3>
            <div class="muted">Special Contributions → Add amount + purpose → Save.</div>

            <h3>Print a receipt</h3>
            <div class="muted">Receipts → View/Print → Download if needed.</div>

            <h3>Run reports</h3>
            <div class="muted">Reports → Export PDF/CSV or open detailed report.</div>
        @endif
    </div>
</body>
</html>
