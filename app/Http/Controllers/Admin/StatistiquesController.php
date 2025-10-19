<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Demande;
use Illuminate\Http\Request;

class StatistiquesController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_conges' => Demande::count(),
            'conges_en_attente' => Demande::where('statut', 'en_attente')->count(),
            'conges_approuves' => Demande::where('statut', 'approuve')->count(),
        ];

        return view('admin.statistiques', compact('stats'));
    }
}
