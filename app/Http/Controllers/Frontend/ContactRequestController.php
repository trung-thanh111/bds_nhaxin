<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactRequestController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * AJAX: Store contact request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'subject' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);

        $validated['status'] = $validated['status'] ?? 'pending';

        ContactRequest::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Yêu cầu của bạn đã được gửi thành công. Chúng tôi sẽ liên hệ lại sớm nhất có thể.'
            ]);
        }

        return redirect()->back()->with('success', 'Yêu cầu của bạn đã được gửi thành công.');
    }
}
