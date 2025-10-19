<?php

namespace App\Http\Controllers;

use App\Models\DemandeJouissance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeJouissanceController extends Controller
{
    public function index()
    {
        $demandes = DemandeJouissance::with(['user', 'demandeConge'])
            ->where('responsable_id', Auth::id())
            ->where('statut', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('jouissance.index', compact('demandes'));
        
    }

    public function show(DemandeJouissance $demandeJouissance)
    {
        $demandeJouissance->load(['user', 'demandeConge']);
        return view('jouissance.show', compact('demandeJouissance'));
    }

    public function approuver(DemandeJouissance $demandeJouissance)
    {
        $demandeJouissance->update([
            'statut' => 'approuve',
            'traite_le' => now()
        ]);

        return back()->with('success', 'Demande de jouissance approuvée');
    }

    public function rejeter(Request $request, DemandeJouissance $demandeJouissance)
    {
        $demandeJouissance->update([
            'statut' => 'rejete',
            'commentaire_responsable' => $request->commentaire,
            'traite_le' => now()
        ]);

        return back()->with('success', 'Demande de jouissance rejetée');
    }
    
}