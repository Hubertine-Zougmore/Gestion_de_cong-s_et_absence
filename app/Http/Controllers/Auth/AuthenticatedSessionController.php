<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   

public function store(Request $request): RedirectResponse
{
    $request->validate([
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
     $request->authenticate();
    $request->session()->regenerate();

    $user = Auth::user();

    /*dd([
        'user_id' => $user->id,
        'user_role' => $user->role,
        'user_attributes' => $user->getAttributes(),
        'user_class' => get_class($user)
    ]);*/

    // Redirection selon le rôle
    switch ($user->role) {
    case 'admin':
        return redirect()->route('admin.dashboard');
    case 'agent':
        return redirect()->route('agent.dashboard');
    case 'drh':
        return redirect()->route('drh.dashboard');
    case 'responsable':
        return redirect()->route('responsable.dashboard');
    case 'president':
        return redirect()->route('president.dashboard');
    case 'sg':
        return redirect()->route('sg.dashboard');
    default:
        return redirect()->route('dashboard');

    

        /*ajoute d'autres cas selon tes rôles
        case 'president':
            return redirect()->route('president.dashboard');

        case 'sg':
            return redirect()->route('sg.dashboard');
        case 'responsable':
            return redirect()->route('responsable.dashboard');
        default:
            return redirect()->route('dashboard');*/
    }
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
