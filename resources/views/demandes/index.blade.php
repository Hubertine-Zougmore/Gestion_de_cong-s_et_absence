@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Bienvenue, {{ Auth::user()->prenom }} {{ Auth::user()->nom }}</h1>
            <p class="text-gray-600">Vous êtes connecté en tant 
                @if(Auth::user()->role === 'agent')
                    <strong>Agent</strong>
                @elseif(Auth::user()->role === 'responsable_hierarchique')
                    <strong>Responsable Hiérarchique</strong>
                @else
                    <strong>{{ Auth::user()->role }}</strong>
                @endif
            </p>
        </div>
        
        <a href="{{ route('demandes.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            ➕ Nouvelle demande
        </a>
    </div>

    <h2 class="text-xl font-semibold mb-4">
        @if(Auth::user()->role === 'agent')
            Mes demandes
        @else
            Mes demandes personnelles
        @endif
    </h2>
    
    @if($demandes->count() > 0)
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        @if(Auth::user()->role !== 'agent')
                        <th class="p-3 text-left">Employé</th>
                        @endif
                        <th class="p-3 text-left">Type</th>
                        <th class="p-3 text-left">Début</th>
                        <th class="p-3 text-left">Fin</th>
                        <th class="p-3 text-left">Jours</th>
                        <th class="p-3 text-left">Statut</th>
                       <th class="p-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($demandes as $demande)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            @if(Auth::user()->role !== 'agent')
                            <td class="p-3">
                                {{ $demande->user->prenom }} {{ $demande->user->nom }}
                            </td>
                            @endif
                            <td class="p-3 capitalize">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $demande->type === 'conge' ? 'bg-green-100 text-green-800' : 
                                       ($demande->type === 'absence' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $demande->type }}
                                </span>
                            </td>
                            <td class="p-3">{{ $demande->date_debut->format('d/m/Y') }}</td>
                            <td class="p-3">{{ $demande->date_fin->format('d/m/Y') }}</td>
                            <td class="p-3 font-semibold">
                                {{ $demande->date_debut->diffInDays($demande->date_fin) + 1 }} jours
                            </td>
                            <td class="p-3">
                                @if ($demande->statut === 'en_attente')
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-semibold">En attente</span>
                                @elseif ($demande->statut === 'approuve')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Approuvée</span>
                                @elseif ($demande->statut === 'refuse')
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Refusée</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-semibold">{{ $demande->statut }}</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <div class="flex space-x-2">
                                    <a href="{{ route('demandes.show', $demande->id) }}" class="text-blue-500 hover:text-blue-700" title="Voir les détails">
                                        👁️ Voir
                                    </a>
                                    
                                    @if(Auth::user()->role === 'agent' && $demande->statut === 'en_attente')
                                        <a href="{{ route('demandes.edit', $demande->id) }}" class="text-green-500 hover:text-green-700" title="Modifier">
                                             Modifier
                                        </a>
                                        <form action="{{ route('demandes.destroy', $demande->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?')"
                                                    title="Supprimer">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                    
                                    {{--  @if(Auth::user()->role === 'responsable_hierarchique' && $demande->statut === 'en_attente')
                                        <form action="{{ route('responsable.demandes.approuver', $demande) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-500 hover:text-green-700" 
                                                    onclick="return confirm('Approuver cette demande ?')"
                                                    title="Approuver">
                                                ✅ Approuver
                                            </button>
                                        </form>
                                        <form action="{{ route('responsable.demandes.rejeter', $demande) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-500 hover:text-red-700" 
                                                    onclick="return confirm('Refuser cette demande ?')"
                                                    title="Refuser">
                                                ❌ Refuser
                                            </button>
                                        </form>
                                    @endif--}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        

    @else
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-gray-500 mb-4">
                @if(Auth::user()->role === 'agent')
                    Vous n'avez aucune demande pour le moment.
                @else
                    Aucune demande dans votre département.
                @endif
            </p>
            <a href="{{ route('demandes.create') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Créer une demande
            </a>
        </div>
    @endif
</div>
@endsection