@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    <!-- 🔹 Bouton de retour -->
    <div class="mb-6">
        <a href="{{ url()->previous() }}" 
           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-700 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:from-blue-600 hover:to-blue-800 transition-all duration-200 shadow-md hover:shadow-lg">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Retour
        </a>
    </div>

    <!-- 🔹 En-tête -->
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
                    @default bg-gray-100 text-gray-800
                @endswitch">
                {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
            </span>
        </div>

        <!-- 🔹 Contenu principal -->
        <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- 🧍 Informations employé -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100 shadow-sm">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Informations employé</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="font-medium text-blue-700">Nom complet</span>
                        <span class="text-blue-900 font-semibold">
                            {{ $demande->user->prenom ?? '' }} {{ $demande->user->nom ?? '' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-blue-700">Email</span>
                        <a href="mailto:{{ $demande->user->email ?? '' }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                           {{ $demande->user->email ?? '' }}
                        </a>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-blue-700">Rôle</span>
                        <span class="text-blue-900">
                            {{ $demande->user->role ?? 'Non spécifié' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-blue-700">Direction</span>
                        <span class="text-blue-900">
                            {{ $demande->user->direction ?? 'Non spécifié' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- 📋 Détails de la demande -->
            <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-6 border border-gray-200 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Détails de la demande</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Type</span>
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                            {{ $demande->type === 'conge' ? 'bg-green-100 text-green-800' : ($demande->type === 'absence' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                            {{ ucfirst($demande->type) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Date début</span>
                        <span class="text-gray-900 font-medium">{{ $demande->date_debut_formatee ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Date fin</span>
                        <span class="text-gray-900 font-medium">{{ $demande->date_fin_formatee ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Durée</span>
                        <span class="text-gray-900 font-bold">{{ $demande->nombre_jours ?? $demande->duree }} jour(s)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 💬 Motif -->
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
                <p class="text-gray-700 leading-relaxed">
                    {{ $demande->motif ?? 'Aucun motif spécifié' }}
                </p>
            </div>
        </div>

        <!-- 📎 Justificatif -->
        <div class="mt-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200 shadow-sm">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Justificatif</h3>
            @if($demande->justificatif)
                <div class="flex items-center space-x-3">
                    <a href="{{ route('demandes.viewJustificatif', $demande->id) }}" 
                       target="_blank"
                       class="text-blue-600 hover:text-blue-800 font-medium">
                        👁️ Voir
                    </a>
                    <a href="{{ route('demandes.downloadJustificatif', $demande->id) }}" 
                       class="text-green-600 hover:text-green-800 font-medium">
                        ⬇️ Télécharger
                    </a>
                </div>
            @else
                <p class="text-gray-600">Aucun justificatif fourni</p>
            @endif
        </div>

        <!-- 🕓 Historique -->
        <div class="mt-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Historique</h3>
            <div class="bg-white p-5 rounded-lg border border-blue-100">
                <p class="text-sm text-gray-700">
                    Soumise le 
                    <strong>{{ $demande->created_at->format('d/m/Y à H:i') }}</strong> 
                    par 
                    <span class="text-gray-900 font-semibold">{{ $demande->user->prenom ?? '' }} {{ $demande->user->nom ?? '' }}</span>
                </p>

                @if($demande->traitee_le)
                    <div class="mt-4 border-t border-gray-100 pt-3">
                        <p class="text-sm text-gray-700">Traîtée le 
                            <strong>{{ $demande->traitee_le->format('d/m/Y H:i') }}</strong>
                        </p>
                        @if($demande->commentaire_president)
                            <p class="text-sm text-gray-700 mt-2 italic">« {{ $demande->commentaire_president }} »</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
