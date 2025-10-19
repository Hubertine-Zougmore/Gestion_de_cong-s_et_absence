@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Bouton de retour -->
    <div class="mb-6">
        <a href="{{ route('president.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-700 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:from-blue-600 hover:to-blue-800 transition-all duration-200 shadow-md hover:shadow-lg">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Retour 
        </a>
    </div>

    <!-- En-tête de la demande -->
    <div class="bg-white shadow-xl rounded-2xl mb-8 overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-5 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                Détails de la demande
            </h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                @switch($demande->statut)
                    @case('en_attente') bg-yellow-100 text-yellow-800 @break
                    @case('approuve') bg-green-100 text-green-800 @break
                    @case('refuse') bg-red-100 text-red-800 @break
                @endswitch">
                {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
            </span>
        </div>

        <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informations employé -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100 shadow-sm">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Informations employé</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="font-medium text-blue-700">Nom complet</span>
                        <span class="text-blue-900 font-semibold">{{ $demande->user->prenom ?? '' }} {{ $demande->user->nom ?? '' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-blue-700">Email</span>
                        <a href="mailto:{{ $demande->user->email ?? '' }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            {{ $demande->user->email ?? '' }}
                        </a>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-blue-700">Rôle</span>
                        <span class="text-blue-900">
                            {{ $demande->user && $demande->user->role ? (is_object($demande->user->role) ? $demande->user->role->nom : $demande->user->role) : 'Non spécifié' }}
                        </span>
                    </div>
 <!-- Information Direction -->
            <div class="mt-6 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-5 border border-gray-200">
                <div class="flex items-center">
                    <div class="bg-gray-100 p-3 rounded-xl mr-4">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-4 0H9m4 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10m4 0h4m-4 0V9"></path>
                        </svg>
                    </div>
                    <div class="flex-1 flex justify-between items-center">
                        <span class="font-medium text-gray-700">Direction</span>
                        <span class="text-gray-900 font-semibold bg-white px-3 py-1 rounded-lg border border-gray-200">
                            @if($demande->user && $demande->user->direction && is_object($demande->user->direction))
                                {{ $demande->user->direction->nom }}
                            @else
                                @php
                                    $nomDirection = 'Non spécifié';
                                    if ($demande->user && $demande->user->direction) {
                                        if (is_object($demande->user->direction) && property_exists($demande->user->direction, 'nom')) {
                                            $nomDirection = $demande->user->direction->nom;
                                        } elseif (is_string($demande->user->direction)) {
                                            $nomDirection = $demande->user->direction;
                                        }
                                    }
                                @endphp
                                {{ $nomDirection }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
                </div>
            </div>

            <!-- Détails de la demande -->
            <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-6 border border-gray-200 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Détails de la demande</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Type</span>
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                            {{ ($demande->type === 'conge') ? 'bg-green-100 text-green-800' : (($demande->type === 'absence') ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                            {{ ucfirst($demande->type) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Date début</span>
                        <span class="text-gray-900 font-medium">{{ $demande->date_debut_formatee }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Date fin</span>
                        <span class="text-gray-900 font-medium">{{ $demande->date_fin_formatee }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Nombre de jours</span>
                        <span class="text-gray-900 font-bold text-lg">{{ $demande->nombre_jours }}</span>
                    </div>
                    <!-- Motif de la demande -->
            <div class="mt-6 bg-gradient-to-br from-white to-purple-50 rounded-xl p-6 border border-gray-200">
                <div class="flex items-center mb-5">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Motif de la demande</h3>
                </div>
                
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-gray-700 leading-relaxed">{{ $demande->motif ?? 'Aucun motif spécifié' }}</p>
                </div>
            </div>
        </div>
    </div>
                </div>
            </div>
        </div>

        <!-- Permissions président -->
        @if($demande->statut === 'en_attente')
        <div class="mt-6 p-5 rounded-xl border {{ $demande->peut_traiter ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200' }}">
            <div class="flex items-center">
                <svg class="h-6 w-6 {{ $demande->peut_traiter ? 'text-green-500' : 'text-yellow-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $demande->peut_traiter ? 'M9 12l2 2 4-4' : 'M12 9v2m0 4h.01' }}" />
                </svg>
                <span class="ml-3 text-sm font-medium">
                    {{ $demande->peut_traiter ? 'Vous pouvez traiter cette demande' : 'Vous ne pouvez pas traiter cette demande' }}
                </span>
            </div>
        </div>
        @endif

        <!-- Actions président -->
        @if($demande->statut === 'en_attente' && $demande->peut_traiter)
        <div class="mt-8 bg-gradient-to-br from-gray-50 to-blue-50 p-6 rounded-xl border border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-6">
            <form action="{{ route('president.demande.approuver', $demande) }}" method="POST" class="border border-green-200 rounded-xl p-5 bg-white shadow-sm">
                @csrf
                <h4 class="text-md font-medium text-green-800 mb-4">Approuver la demande</h4>
                <textarea name="commentaire" rows="3" placeholder="Commentaire optionnel" class="w-full border border-green-300 rounded-lg p-3">{{ $demande->commentaire_president ?? '' }}</textarea>
                <button type="submit" class="mt-3 w-full bg-green-600 text-white rounded-lg py-2 hover:bg-green-700">Approuver</button>
            </form>

            <form action="{{ route('president.demande.rejeter', $demande) }}" method="POST" class="border border-red-200 rounded-xl p-5 bg-white shadow-sm">
                @csrf
                <h4 class="text-md font-medium text-red-800 mb-4">Refuser la demande</h4>
                <textarea name="commentaire" rows="3" placeholder="Motif du refus" required class="w-full border border-red-300 rounded-lg p-3">{{ $demande->commentaire_president ?? '' }}</textarea>
                <button type="submit" class="mt-3 w-full bg-red-600 text-white rounded-lg py-2 hover:bg-red-700">Refuser</button>
            </form>
        </div>
        @endif

        <!-- Historique -->
        @if($demande->traitee_le)
        <div class="mt-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Historique de traitement</h3>
            <div class="bg-white p-5 rounded-lg border border-blue-100">
                <div class="flex justify-between">
                    <div>
                        <p class="text-sm text-gray-700 font-medium">Traîtée par :</p>
                        <p class="text-sm text-gray-900">{{ $demande->traiteParPresident ? $demande->traiteParPresident->prenom . ' ' . $demande->traiteParPresident->nom : 'Système' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700 font-medium">Date de traitement :</p>
                        <p class="text-sm text-gray-900">{{ $demande->traitee_le->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @if($demande->commentaire_president)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-700 font-medium">Commentaire :</p>
                    <p class="text-sm text-gray-900 mt-1">{{ $demande->commentaire_president }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
