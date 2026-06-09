<?php
namespace App\Http\Controllers\Web\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller {
    public function show(string $lang): View {
        return view('web.auth.register', ['lang' => $lang]);
    }
    public function store(RegisterRequest $request, string $lang): RedirectResponse {
        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'company'  => $request->input('company'),
            'phone'    => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
            'locale'   => $lang,
        ]);
        $user->forceFill(['gdpr_consent_at' => now()])->save();

        \App\Support\AccountNotifications::pending($user);

        return redirect()->route('login', $lang)->with('registered', true);
    }
}
