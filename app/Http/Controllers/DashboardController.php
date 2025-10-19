<?php

namespace App\Http\Controllers;  // Ligne cruciale

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
}