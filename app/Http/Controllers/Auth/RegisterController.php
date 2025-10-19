<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
        'first_nom' => ['required', 'string', 'max:255'],
            'last_nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'department' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
        'nom' => $data['nom'],
        'prenom' => $data['prenom'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
        'matricule' => $data['matricule'],
        'phone' => $data['phone'],
        'department' => $data['department'],
        'password' => Hash::make($data['password']),
        ]);
         // Attribution automatique du rôle
    $user->assignRole('user'); // Ou le rôle par défaut
    
    return $user;
    }

    protected function redirectTo()
    {
        return '/agent/dashboard';
    }
}