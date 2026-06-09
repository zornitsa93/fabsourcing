<?php
namespace App\Http\Controllers\Web\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller {
    public function show(string $lang): View {
        return view('web.auth.login', ['lang' => $lang]);
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

        if ($user && ! $user->isApproved()) {
            throw ValidationException::withMessages(['email' => $pending]);
        }
        if (! Auth::attempt($data, $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => $invalid]);
        }
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
