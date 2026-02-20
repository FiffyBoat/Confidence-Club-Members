<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReceiptController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('q');

        $query = Receipt::with('member')->latest();
        if ($search) {
            $query->where(function ($builder) use ($search) {
                $builder->where('receipt_number', 'like', '%'.$search.'%')
                    ->orWhereHas('member', function ($memberBuilder) use ($search) {
                        $memberBuilder->where('full_name', 'like', '%'.$search.'%')
                            ->orWhere('membership_id', 'like', '%'.$search.'%');
                    });
            });
        }

        $receipts = $query->paginate(15)->withQueryString();

        return view('receipts.index', compact('receipts', 'search'));
    }

    public function show(Receipt $receipt): View
    {
        $receipt->load(['member', 'generatedBy']);

        return view('receipts.show', compact('receipt'));
    }

    public function download(Receipt $receipt)
    {
        $disk = config('filesystems.receipts_disk', 'public');

        return Storage::disk($disk)->download($receipt->pdf_path, $receipt->receipt_number.'.pdf');
    }

    public function view(Receipt $receipt)
    {
        $disk = config('filesystems.receipts_disk', 'public');

        return Storage::disk($disk)->response(
            $receipt->pdf_path,
            $receipt->receipt_number.'.pdf',
            ['Content-Disposition' => 'inline; filename="'.$receipt->receipt_number.'.pdf"']
        );
    }
}
