@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Toutes les Demandes</h1>
            <p class="text-gray-600">Liste de toutes les demandes de l'entreprise</p>
        </div>

        <div class="px-6 py-5">
            @if($demandes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employé</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Département</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Période</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($demandes as $demande)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $demande->user->prenom }} {{ $demande->user->nom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $demande->user->departement }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $demande->type === 'conge_annuel' ? 'bg-green-100 text-green-800' : 
                                           ($demande->type === 'absence' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ str_replace('_', ' ', $demande->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $demande->date_debut_formatee }} au {{ $demande->date_fin_formatee }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">
                                    {{ $demande->nombre_jours }} jours
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($demande->statut === 'en_attente')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            En attente
                                        </span>
                                    @elseif($demande->statut === 'approuve')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Approuvé
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Refusé
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('demandes.show', $demande->id) }}" class="text-blue-600 hover:text-blue-800">
                                        Voir
                                    </a>
                                    @if($demande->statut === 'en_attente' && Auth::user()->role === 'drh')
                                        <form action="{{ route('drh.demande.approuver', $demande->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="ml-2 text-green-600 hover:text-green-800">
                                                Approuver
                                            </button>
                                        </form>
                                        <form action="{{ route('drh.demande.rejeter', $demande->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="ml-2 text-red-600 hover:text-red-800">
                                                Rejeter
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">Aucune demande trouvée.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection