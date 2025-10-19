@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        @if(Auth::user()->role === 'agent')
                            Mes Demandes de Congé
                        @elseif(Auth::user()->role === 'responsable_hierarchique')
                            Demandes de mon Département
                        @elseif(Auth::user()->role === 'drh')
                            Gestion des Demandes
                        @else
                            Demandes
                        @endif
                    </h1>
                    <p class="text-gray-600 mt-1">
                        @if(Auth::user()->role === 'agent')
                            Liste de toutes mes demandes de congé
                        @elseif(Auth::user()->role === 'responsable_hierarchique')
                            Demandes du département {{ Auth::user()->departement }}
                        @elseif(Auth::user()->role === 'drh')
                            Toutes les demandes de l'entreprise
                        @endif
                    </p>
                </div>
                
                @if(in_array(Auth::user()->role, ['agent', 'drh', 'responsable_hierarchique']))
                <a href="{{ route('demandes.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nouvelle Demande
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    @if(isset($stats) && count($stats) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['total_demandes'] ?? 0 }}</div>
            <div class="text-gray-600">Total</div>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['en_attente'] ?? 0 }}</div>
            <div class="text-gray-600">En Attente</div>
        </div>
        <div class="bg-green-50 p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-green-600">{{ $stats['approuvees'] ?? 0 }}</div>
            <div class="text-gray-600">Approuvées</div>
        </div>
        <div class="bg-red-50 p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-red-600">{{ $stats['rejetees'] ?? 0 }}</div>
            <div class="text-gray-600">Rejetées</div>
        </div>
    </div>
    @endif

    <!-- Tableau des demandes -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($demandes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            @if(Auth::user()->role !== 'agent')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Employé
                            </th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Période
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jours
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date demande
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($demandes as $demande)
                        <tr class="hover:bg-gray-50">
                            @if(Auth::user()->role !== 'agent')
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $demande->user->prenom }} {{ $demande->user->nom }}
                                <div class="text-xs text-gray-500">{{ $demande->user->departement }}</div>
                            </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $demande->type === 'conge_annuel' ? 'bg-green-100 text-green-800' : 
                                       ($demande->type === 'conge_maladie' ? 'bg-blue-100 text-blue-800' :
                                       ($demande->type === 'conge_maternite' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ str_replace('_', ' ', $demande->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($demande->date_debut)->format('d/m/Y') }}<br>
                                <span class="text-gray-500">au</span><br>
                                {{ \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                {{ $demande->nombre_jours }} jours
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($demande->statut === 'en_attente')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        En attente
                                    </span>
                                @elseif($demande->statut === 'approuve')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Approuvé
                                    </span>
                                @elseif($demande->statut === 'refuse' || $demande->statut === 'rejete')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Refusé
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $demande->statut }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $demande->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('demandes.show', $demande->id) }}" class="text-blue-600 hover:text-blue-800" title="Voir les détails">
                                        👁️ Voir
                                    </a>
                                    
                                    @if(Auth::user()->role === 'agent' && $demande->statut === 'en_attente')
                                        <a href="{{ route('demandes.edit', $demande->id) }}" class="text-green-600 hover:text-green-800" title="Modifier">
                                            Modifier
                                        </a>
                                        <form action="{{ route('demandes.destroy', $demande->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?')"
                                                    title="Supprimer">
                                                🗑️ Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            

        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune demande</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(Auth::user()->role === 'agent')
                        Vous n'avez pas encore fait de demandes de congé.
                    @else
                        Aucune demande à afficher pour le moment.
                    @endif
                </p>
                <div class="mt-6">
                    <a href="{{ route('demandes.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Créer une demande
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection