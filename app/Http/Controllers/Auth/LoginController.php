<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;
       protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
  /**
     * Valider les credentials de l'utilisateur
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Obtenir les credentials nécessaires pour la tentative de connexion
     */
    protected function credentials(Request $request)
    {
        return [
            'email' => $request->email,
            'password' => $request->password,
            'is_active' => true, // ✅ IMPORTANT : vérifier que le compte est actif
        ];
    }

    /**
     * Gérer une tentative de connexion échouée
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Vérifier si l'utilisateur existe mais est désactivé
        $user = User::where('email', $request->email)->first();

        if ($user && !$user->is_active) {
            throw ValidationException::withMessages([
                $this->username() => [__('Votre compte a été désactivé. Veuillez contacter l\'administrateur.')],
            ]);
        }

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Tentative de connexion
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Vérifier manuellement si l'utilisateur est actif
        $user = User::where('email', $request->email)->first();

        if ($user && !$user->is_active) {
            return $this->sendFailedLoginResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function authenticated(Request $request, $user)
    {
        // Redirection basée sur le rôle
        switch ($user->role) {
            case 'drh':
                return redirect()->route('drh.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'responsable_hierarchique':
                return redirect()->route('responsable.dashboard');
            default:
            if ($user->role === 'responsable_hierarchique') {
        return redirect()->route('responsable.dashboard');
    } elseif ($user->role === 'agent') {
        return redirect()->route('agent.dashboard');
    }
                return redirect()->route('home');
        }
    }

   // protected $redirectTo = '/home';

    // ...
}