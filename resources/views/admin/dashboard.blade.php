@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Bienvenue ! {{ Auth::user()->name }}</h1>
    <p class="text-gray-600 mb-6">Vous êtes connecté en tant qu’<strong>Administrateur</strong>.</p>

   
<li>
<a href="{{ route('admin.utilisateurs.index') }}"
   class="bg-blue-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-block mb-4">
        👥 Gérer les comptes des utilisateurs
    </a>
</li>

<li>
<a href="{{ route('admin.parametres.index') }}"
   class="bg-blue-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-block mb-4">
    Paramétrer les types de congés et les quotas
</a>



<!--<div class="mt-10">
    <h2 class="text-xl font-semibold mb-4">Dernières demandes</h2>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Agent</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Début</th>
                    <th class="p-3 text-left">Fin</th>
                    <th class="p-3 text-left">Statut</th>
                </tr>
            </thead>
            <tbody>
                @isset($dernieresDemandes)
                    @forelse ($dernieresDemandes as $demande)
                        <tr class="border-t">
                            <td class="p-3">{{ $demande->user->name ?? 'N/A' }}</td>
                            <td class="p-3">{{ ucfirst($demande->type ?? '') }}</td>
                            <td class="p-3">{{ $demande->date_debut->format('d/m/Y') ?? '' }}</td>
                            <td class="p-3">{{ $demande->date_fin->format('d/m/Y') ?? '' }}</td>
                            <td class="p-3">
                                @if ($demande->statut === 'en attente')
                                    <span class="text-yellow-500 font-semibold">En attente</span>
                                @elseif ($demande->statut === 'approuve')
                                    <span class="text-green-600 font-semibold">Approuvée</span>
                                @else
                                    <span class="text-red-600 font-semibold">Rejetée</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-3 text-center text-gray-500">Aucune demande récente.</td>
                        </tr>
                    @endforelse
                @else
                    <tr>
                        <td colspan="5" class="p-3 text-center text-red-500">Erreur de chargement des données.</td>
                    </tr>
                @endisset
            </tbody>-->
        </table>
    </div>
</div>
@endsection
