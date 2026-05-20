<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;

class ContactSubmissionsController extends Controller
{
    public function index()
    {
        $submissions = ContactSubmission::orderByDesc('created_at')->paginate(30);
        $unreadCount = ContactSubmission::where('is_read', false)->count();

        return view('admin.contact-submissions.index', compact('submissions', 'unreadCount'));
    }

    public function show(ContactSubmission $contactSubmission)
    {
        if (! $contactSubmission->is_read) {
            $contactSubmission->update(['is_read' => true]);
        }

        return view('admin.contact-submissions.show', compact('contactSubmission'));
    }

    public function markResponded(ContactSubmission $contactSubmission)
    {
        $contactSubmission->update([
            'is_responded' => true,
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Демандата е отбелязана като отговорена.');
    }

    public function destroy(ContactSubmission $contactSubmission)
    {
        $contactSubmission->delete();

        return redirect()->route('contact-submissions.index')
            ->with('success', 'Демандата е изтрита.');
    }
}
