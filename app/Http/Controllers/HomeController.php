<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Redirection selon le rôle
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'agent' => redirect()->route('agent.dashboard'),
            'drh' => redirect()->route('drh.dashboard'),
            'responsable' => redirect()->route('responsable.dashboard'),
            'president' => redirect()->route('president.dashboard'),
            'sg' => redirect()->route('sg.dashboard'),
            default => view('home')
        };
    }
}