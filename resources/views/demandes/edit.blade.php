@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6">Modifier la demande de congé</h1>

        <!-- Vérification que la variable $demande existe -->
        @if(!isset($demande))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <p>Erreur: Demande introuvable.</p>
                <a href="{{ route('demandes.index') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                    ← Retour à la liste des demandes
                </a>
            </div>
        @else
            <!-- Formulaire d'édition -->
            <form action="{{ route('demandes.update', $demande->id) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded-lg">
                @csrf
                @method('PUT')
                
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Informations de la demande</h2>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Grille pour les champs principaux -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Type de demande -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Type de demande
                            </label>
                            <select id="type" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Sélectionner un type</option>
                                <option value="conge_annuel" {{ $demande->type === 'conge_annuel' ? 'selected' : '' }}>Congé annuel</option>
                                <option value="conge_maternite" {{ $demande->type === 'conge_maternite' ? 'selected' : '' }}>Congé de maternité</option>
                                <option value="Autorisation_absence" {{ $demande->type === 'Autorisation_absence' ? 'selected' : '' }}>Autorisation d'absence</option>
                                //<option value="conge_maladie" {{ $demande->type === 'conge_maladie' ? 'selected' : '' }}>Congé de maladie</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Statut (lecture seule si pas DRH) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Statut actuel
                            </label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-md">
                                @switch($demande->statut)
                                    @case('en_attente')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            🕒 En attente
                                        </span>
                                        @break
                                    @case('approuve')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✅ Approuvé
                                        </span>
                                        @break
                                    @case('rejete')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            ❌ Rejeteé
                                        </span>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Date de début -->
                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">
                                Date de début
                            </label>
                            <input type="date" 
                                   id="date_debut" 
                                   name="date_debut" 
                                   value="{{ $demande->date_debut->format('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                   required>
                            @error('date_debut')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date de fin -->
                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">
                                Date de fin
                            </label>
                            <input type="date" 
                                   id="date_fin" 
                                   name="date_fin" 
                                   value="{{ $demande->date_fin->format('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                   required>
                            @error('date_fin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Calcul automatique des jours -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm text-blue-800">
                                <strong>Durée calculée :</strong> <span id="duree-calculee">{{ $demande->date_debut->diffInDays($demande->date_fin) + 1 }} jour(s)</span>
                            </span>
                        </div>
                    </div>

                    <!-- Motif -->
                    <div>
                        <label for="motif" class="block text-sm font-medium text-gray-700 mb-2">
                            Motif de la demande
                        </label>
                        <textarea id="motif" 
                                  name="motif" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                  placeholder="Expliquez le motif de votre demande...">{{ old('motif', $demande->motif) }}</textarea>
                        @error('motif')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Commentaire DRH (lecture seule) -->
                    @if($demande->commentaire_drh)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Commentaire DRH
                            </label>
                            <div class="bg-gray-50 border border-gray-300 rounded-md p-3">
                                <p class="text-sm text-gray-900">{{ $demande->commentaire_drh }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            * Seules les demandes "En attente" peuvent être modifiées
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('demandes.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Annuler
                            </a>
                            
                            @if($demande->statut === 'en_attente' || $demande->statut === 'en attente')
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Modifier la demande
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateDebutInput = document.getElementById('date_debut');
    const dateFinInput = document.getElementById('date_fin');
    const dureeCalculeeSpan = document.getElementById('duree-calculee');

    // Fonction pour calculer le nombre de jours
    function calculerNombreJours() {
        if (dateDebutInput.value && dateFinInput.value) {
            const dateDebut = new Date(dateDebutInput.value);
            const dateFin = new Date(dateFinInput.value);
            
            // Calcul de la différence en jours
            const diffTime = Math.abs(dateFin - dateDebut);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 pour inclure le premier jour
            
            // Mise à jour de l'affichage
            dureeCalculeeSpan.textContent = diffDays + ' jour(s)';
        } else {
            dureeCalculeeSpan.textContent = '0 jour(s)';
        }
    }

    // Écouter les changements de dates
    dateDebutInput.addEventListener('change', calculerNombreJours);
    dateFinInput.addEventListener('change', calculerNombreJours);

    // Initialiser le calcul au chargement
    calculerNombreJours();
});
</script>
@endsection