<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountsController extends Controller {
    public function index(): View {
        $pending  = User::whereNull('approved_at')->latest()->get();
        $approved = User::whereNotNull('approved_at')->latest('approved_at')->get();
        return view('admin.accounts.index', compact('pending', 'approved'));
    }
    public function approve(User $account): RedirectResponse {
        $account->forceFill(['approved_at' => now(), 'approved_by' => Auth::guard('admin')->id()])->save();
        // A later task wires the flag-gated "approved" email here:
        // \App\Support\AccountNotifications::approved($account);
        return back()->with('status', 'approved');
    }
    public function revoke(User $account): RedirectResponse {
        $account->forceFill(['approved_at' => null, 'approved_by' => null])->save();
        return back()->with('status', 'revoked');
    }
    public function destroy(User $account): RedirectResponse {
        $account->delete();
        return back()->with('status', 'deleted');
    }
}
