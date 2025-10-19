@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Bouton de retour -->
    <div class="mb-6">
    <a href="{{ url('/secretaire_general/dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-700 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:from-blue-600 hover:to-blue-800 transition-all duration-200 shadow-md hover:shadow-lg">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour au tableau de bord
    </a>
</div>

    <!-- En-tête de la demande -->
    <div class="bg-white shadow-xl rounded-2xl mb-8 overflow-hidden border border-gray-100">
        <!-- Header avec fond dégradé -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-6 py-5">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Détails de la demande - Secrétaire Général
                </h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                    @switch($demande->statut)
                        @case('en_attente') bg-yellow-100 text-yellow-800 @break
                        @case('approuvee') bg-green-100 text-green-800 @break
                        @case('rejetee') bg-red-100 text-red-800 @break
                    @endswitch">
                    {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
                </span>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Carte Informations employé -->
                <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-100 shadow-sm">
                    <div class="flex items-center mb-5">
                        <div class="bg-purple-100 p-3 rounded-xl">
                            <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-purple-900">Informations employé</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-purple-100">
                            <span class="font-medium text-purple-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Nom complet
                            </span>
                            <span class="text-purple-900 font-semibold">{{ $demande->user->prenom ?? '' }} {{ $demande->user->nom ?? '' }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-purple-100">
                            <span class="font-medium text-purple-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Email
                            </span>
                            <a href="mailto:{{ $demande->user->email ?? '' }}" class="text-purple-600 hover:text-purple-800 transition-colors font-medium">
                                {{ $demande->user->email ?? '' }}
                            </a>
                        </div>
                        
                        <div class="flex justify-between items-center py-2">
                            <span class="font-medium text-purple-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Rôle
                            </span>
                            <span class="text-purple-900">
                                @if($demande->user && $demande->user->role)
                                    {{ $demande->user->role }}
                                @else
                                    Non spécifié
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Carte Détails de la demande -->
                <div class="bg-gradient-to-br from-gray-50 to-purple-50 rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center mb-5">
                        <div class="bg-gray-100 p-3 rounded-xl">
                            <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Détails de la demande</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="font-medium text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Type
                            </span>
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                {{ ($demande->type === 'conge_annuel') ? 'bg-green-100 text-green-800' : 
                                   (($demande->type === 'conge_maternite') ? 'bg-pink-100 text-pink-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $demande->type)) }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="font-medium text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Date début
                            </span>
                            <span class="text-gray-900 font-medium">{{ $demande->date_debut->format('d/m/Y') }}</span>
                        </div>

                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="font-medium text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Date fin
                            </span>
                            <span class="text-gray-900 font-medium">{{ $demande->date_fin->format('d/m/Y') }}</span>
                        </div>

                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="font-medium text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Nombre de jours
                            </span>
                            <span class="text-gray-900 font-bold text-lg">{{ $demande->nombre_jours }} jours</span>
                        </div>

                        <div class="flex justify-between items-center py-2">
                            <span class="font-medium text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Durée totale
                            </span>
                            <span class="text-sm text-gray-900">
                                <span class="font-semibold {{ $demande->nombre_jours >= 4 ? 'text-purple-600' : 'text-gray-600' }}">
                                    {{ $demande->nombre_jours * 24 }}h
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Direction -->
            <div class="mt-6 bg-gradient-to-r from-gray-50 to-purple-50 rounded-xl p-5 border border-gray-200">
                <div class="flex items-center">
                    <div class="bg-gray-100 p-3 rounded-xl mr-4">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-4 0H9m4 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10m4 0h4m-4 0V9"></path>
                        </svg>
                    </div>
                    <div class="flex-1 flex justify-between items-center">
                        <span class="font-medium text-gray-700">Direction</span>
                        <span class="text-gray-900 font-semibold bg-white px-3 py-1 rounded-lg border border-gray-200">
                            {{ $demande->user->direction ?? 'Non spécifié' }}
                        </span>
                    </div>
                </div>
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

    <!-- INDICATION DES PERMISSIONS POUR LE SECRETAIRE GENERAL -->
    @if($demande->statut === 'en_attente')
    <div class="mt-6 p-5 rounded-xl border 
        @if($demande->nombre_jours >= 4) bg-green-50 border-green-200 
        @else bg-yellow-50 border-yellow-200 @endif">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                @if($demande->nombre_jours >= 4)
                    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @else
                    <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                @endif
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium">
                    @if($demande->nombre_jours >= 4)
                        <span class="text-green-800">📋 Vous pouvez traiter cette demande (Secrétaire Général)</span>
                    @else
                        <span class="text-yellow-800">⏳ Cette demande doit être traitée par le responsable hiérarchique</span>
                    @endif
                </h3>
                <div class="mt-2 text-sm">
                    @if($demande->nombre_jours >= 4)
                        <p class="text-green-700">
                            Raison: Durée ≥ 4 jours (compétence du Secrétaire Général)
                        </p>
                    @else
                        <p class="text-yellow-700">Raison: Durée < 4 jours (traitement par le responsable hiérarchique)</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Actions de traitement POUR LE SECRETAIRE GENERAL -->
    @if($demande->statut === 'en_attente' && $demande->nombre_jours >= 4)
    <div class="mt-8 bg-gradient-to-br from-gray-50 to-purple-50 p-6 rounded-xl border border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-5 flex items-center">
            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Traiter cette demande (Secrétaire Général)
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Formulaire d'approbation -->
            <div class="border border-green-200 rounded-xl p-5 bg-white shadow-sm">
                <h4 class="text-md font-medium text-green-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Approuver la demande
                </h4>
                <form action="{{ route('secretaire_general.demande.approuver', $demande) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="accepter">
                    <div class="mb-4">
                        <label for="commentaire_approbation" class="block text-sm font-medium text-green-700 mb-1">Commentaire (optionnel)</label>
                        <textarea name="commentaire" id="commentaire_approbation" rows="3" 
                                class="mt-1 block w-full border border-green-300 rounded-lg shadow-sm p-3 focus:ring-green-500 focus:border-green-500 transition duration-150" 
                                placeholder="Commentaire d'approbation..."></textarea>
                    </div>
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-3 rounded-lg hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Approuver la demande
                    </button>
                </form>
            </div>

            <!-- Formulaire de refus -->
            <div class="border border-red-200 rounded-xl p-5 bg-white shadow-sm">
                <h4 class="text-md font-medium text-red-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Refuser la demande
                </h4>
                <form action="{{ route('secretaire_general.demande.rejeter', $demande) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="rejeter">
                    <div class="mb-4">
                        <label for="commentaire_refus" class="block text-sm font-medium text-red-700 mb-1">Motif du refus *</label>
                        <textarea name="commentaire" id="commentaire_refus" rows="3" 
                                class="mt-1 block w-full border border-red-300 rounded-lg shadow-sm p-3 focus:ring-red-500 focus:border-red-500 transition duration-150" 
                                required placeholder="Veuillez indiquer le motif du refus..."></textarea>
                    </div>
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-3 rounded-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Rejeter la demande
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Historique des traitements -->
    @if($demande->traite_le)
    <div class="mt-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Historique de traitement
        </h3>
        <div class="bg-white p-5 rounded-lg border border-blue-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-700 font-medium">Traîtée par :</p>
                    <p class="text-sm text-gray-900">{{ $demande->traite_par ? $demande->traitePar?->prenom . ' ' . $demande->traitePar?->nom : 'Système' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-700 font-medium">Date de traitement :</p>
                    <p class="text-sm text-gray-900">{{ $demande->traite_le->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            @if($demande->commentaire_secretaire_general)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-700 font-medium">Commentaire du Secrétaire Général :</p>
                    <p class="text-sm text-gray-900 mt-1">{{ $demande->commentaire_secretaire_general }}</p>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection