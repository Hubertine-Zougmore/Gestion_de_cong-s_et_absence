<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Parametre;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    // Affiche la liste des paramètres
    public function index()
    {
        $parametres = Parametre::all();
        return view('admin.parametres.index', compact('parametres'));
    }

    // Affiche le formulaire de création
    public function create()
    {
        return view('admin.parametres.create');
    }

    // Enregistre un nouveau paramètre
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'valeur' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Parametre::create($request->all());

        return redirect()->route('admin.parametres.index')->with('success', 'Paramètre ajouté avec succès.');
    }

    // Affiche le formulaire d'édition
    public function edit(Parametre $parametre)
    {dd($parametre);
        return view('admin.parametres.edit', compact('parametre'));
    }

    // Met à jour un paramètre
    public function update(Request $request, Parametre $parametre)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'valeur' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $parametre->update($request->all());

        return redirect()->route('admin.parametres.index')->with('success', 'Paramètre mis à jour avec succès.');
    }

    // Supprime un paramètre
    public function destroy(Parametre $parametre)
    {
        $parametre->delete();

        return redirect()->route('admin.parametres.index')->with('success', 'Paramètre supprimé avec succès.');
    }
}
