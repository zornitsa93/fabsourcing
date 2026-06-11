<?php
namespace App\Http\Controllers\Web\Auth;
use App\Http\Controllers\WebPagesController;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends WebPagesController {
    public function show(string $lang): View {
        return view('web.auth.login', $this->commonForWebPages($lang));
    }
    public function login(Request $request, string $lang): RedirectResponse {
        $data = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
        ]);
        $user = User::where('email', $data['email'])->first();
        $pending = $lang === 'fr'
            ? "Votre compte est en attente de validation par notre équipe."
            : 'Your account is awaiting approval by our team.';
        $invalid = $lang === 'fr' ? 'Identifiants invalides.' : 'Invalid credentials.';

        // Verify credentials BEFORE revealing approval status, to avoid account enumeration.
        if (! Auth::validate($data)) {
            throw ValidationException::withMessages(['email' => $invalid]);
        }
        if ($user && ! $user->isApproved()) {
            throw ValidationException::withMessages(['email' => $pending]);
        }
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        return redirect()->intended("/$lang/documents");
    }
    public function logout(Request $request, string $lang): RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home', $lang);
    }
}
