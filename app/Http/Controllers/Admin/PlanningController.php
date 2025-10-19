<?php
// app/Http/Controllers/Admin/PlanningController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demande;

class PlanningController extends Controller
{
    public function index()
    {
        $plannings = Demande::where('statut', 'approuvé')->with('user')->orderBy('date_debut')->get();
        return view('admin.planning', compact('plannings'));
    }
}

