<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PromotionMail;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'customer_ids' => 'required|array',
            'subject'      => 'required|string|max:255',
            'message'      => 'required|string',
            'attachment'   => 'nullable|file|max:5120',
        ]);

        // Store attachment safely (if any)
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')
                ->store('email_attachments', 'public');
        }

        $customers = Customer::whereIn('id', $request->customer_ids)
            ->whereNotNull('email')
            ->get();

        foreach ($customers as $customer) {
            Mail::to($customer->email)->send(
                new PromotionMail(
                    subjectText: $request->subject,
                    content: $request->message,
                    senderName: auth()->user()->name,
                    senderRole: auth()->user()->role,
                    attachmentPath: $attachmentPath
                )
            );
        }

        return back()->with('success', 'Promotion email sent successfully.');
    }
}
