@extends('layouts.app')

@php
    // Normalisation des variables avec valeurs par défaut
    $demandesATraiter = $demandesATraiter ?? collect();
    $mesDemandes = $mesDemandes ?? collect();
    $toutesLesDemandes = $toutesLesDemandes ?? collect();
    $stats = $stats ?? [];
    $departementResponsable = $departementResponsable ?? '';
    $user = Auth::user();
@endphp

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Tableau de Bord Responsable</h1>

    <!-- En-tête -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Bienvenue, {{ $user->prenom ?? 'Utilisateur' }} {{ $user->nom ?? '' }}</h1>
        Vous êtes connecté en tant que <strong>Responsable Hiérarchique</strong> 
    
            </div>
<!-- Cartes statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded shadow">
        <div class="text-blue-600 font-bold text-2xl">
            {{ $stats['total_demandes'] ?? 0 }}
        </div>
        <div class="text-gray-600">Total Demandes</div>
    </div>
    
    <div class="bg-yellow-50 p-4 rounded shadow">
        <div class="text-yellow-600 font-bold text-2xl">
            {{ $stats['en_attente'] ?? 0 }}
        </div>
        <div class="text-gray-600">En Attente</div>
        @if(isset($stats['de_ma_direction']))
            <div class="text-xs text-yellow-500 mt-1">
                {{ $stats['de_ma_direction'] }} de ma direction
            </div>
        @endif
    </div>
    
    <div class="bg-green-50 p-4 rounded shadow">
        <div class="text-green-600 font-bold text-2xl">
            {{ $stats['approuvees'] ?? 0 }}
        </div>
        <div class="text-gray-600">Approuvées</div>
    </div>

    
    <div class="bg-red-50 p-4 rounded shadow">
        <div class="text-red-600 font-bold text-2xl">
            {{ $stats['rejetees'] ?? 0 }}
        </div>
        <div class="text-gray-600">Rejetées</div>
    </div>
    
    <div class="bg-orange-50 p-4 rounded shadow">
        <div class="text-orange-600 font-bold text-2xl">
            {{ $stats['urgentes'] ?? 0 }}
        </div>
        <div class="text-gray-600">Urgentes (<72h)</div>
        @if(isset($stats['urgentes_de_ma_direction']))
            <div class="text-xs text-orange-500 mt-1">
                {{ $stats['urgentes_de_ma_direction'] }} de ma direction
            </div>
        @endif
    </div>
    </div>

    <!-- Onglets -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button class="tab-button px-6 py-3 text-sm font-medium border-b-2 border-blue-500 text-blue-600 whitespace-nowrap" data-tab="demandes-traiter">
                    Demandes à Traiter
                    @if($demandesATraiter->count() > 0)
                        <span class="ml-2 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $demandesATraiter->count() }}</span>
                    @endif
                </button>
                <button class="tab-button px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap" data-tab="mes-demandes">
                    Mes Demandes
                    @if($mesDemandes->count() > 0)
                        <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $mesDemandes->count() }}</span>
                    @endif
                </button>
                <button class="tab-button px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap" data-tab="toutes-demandes">
                    Toutes les Demandes
                    <span class="ml-2 bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $stats['total_demandes_departement'] ?? 0 }}</span>
                </button>
            </nav>
        </div>

        <!-- Contenu onglet Demandes à traiter -->
        <div id="demandes-traiter" class="tab-content p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Demandes à Traiter (<72h)</h2>
                <div class="bg-blue-50 border border-blue-200 rounded-lg px-3 py-1">
                    <p class="text-sm text-blue-700">
                        ℹ️ Je peux traiter uniquement les demandes<br>
                        de ma direction de moins de 72h
                    </p>
                </div>
            </div>
            
             <!-- Tableau des demandes -->
    @if($demandesTraitable->count() > 0)
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
  <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employé</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Direction</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jours</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Demande</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($demandesTraitable as $demande)
                                    <tr class="hover:bg-gray-50 {{ $demande->est_urgent ? 'bg-yellow-50' : '' }}">
                                        <!-- Colonne Employé -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-blue-800 font-medium text-sm">
                                                        {{ substr($demande->user->prenom ?? '', 0, 1) }}{{ substr($demande->user->nom ?? '', 0, 1) }}
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $demande->user->prenom ?? 'N/A' }} {{ $demande->user->nom ?? 'N/A' }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $demande->user->matricule ?? 'Sans matricule' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Colonne Direction -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center text-sm text-gray-900">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-4 0H9m4 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10m4 0h4m-4 0V9"></path>
                                                </svg>
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
                                            </div>
                                        </td>
                                        
                                        <!-- Colonne Type -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $typeColors = [
                                                    'conge_annuel' => 'bg-green-100 text-green-800',
                                                    'conge_maternite' => 'bg-pink-100 text-pink-800',
                                                    'autorisation_absence' => 'bg-blue-100 text-blue-800',
                                                    'default' => 'bg-gray-100 text-gray-800'
                                                ];
                                                $colorClass = $typeColors[$demande->type] ?? $typeColors['default'];
                                            @endphp
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $demande->type)) }}
                                            </span>
                                        </td>
                                        
                                        <!-- Colonne Période -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $demande->date_debut->format('d/m/Y') }}</div>
                                            <div class="text-sm text-gray-500">au {{ $demande->date_fin->format('d/m/Y') }}</div>
                                        </td>
                                        
                                        <!-- Colonne Jours -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100">
                                                {{ $demande->nombre_jours }}
                                            </span>
                                        </td>
                                        <!-- Colonne Date Demande -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $demande->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <!-- statut -->
                                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="font-bold text-blue-600">
                                            {{ $demande->statut }}
                                        </span>
                                    </td>
                                <!-- Colonne Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($demande->peut_traiter)
                                        <a href="{{ route('responsable.demandes-show', $demande) }}" 
                                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Traiter
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed" 
                                              title="Non traitable - Délai insuffisant">
                                            Traiter
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
       {{--   <div class="mt-4">
            {{ $demandesTraitable->links() }}
        </div>--}}
    @else
        <!-- État vide -->
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune demande à traiter</h3>
            <p class="mt-2 text-sm text-gray-500">Toutes les demandes des directions UTS ont été traitées.</p>
        </div>
    @endif
</div>
        <!-- Contenu onglet Mes demandes -->
        <div id="mes-demandes" class="tab-content p-6 hidden">
            <h2 class="text-xl font-semibold mb-4">Mes Demandes</h2>
            
            @if($mesDemandes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Début</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Fin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Demande</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($mesDemandes as $demande)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $demande->type === 'conge' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($demande->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $demande->date_debut_formatee }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $demande->date_fin_formatee }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $demande->nombre_jours }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @switch($demande->statut)
                                            @case('en_attente')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                                                @break
                                            @case('approuve')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approuvé</span>
                                                @break
                                            @case('refuse')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Refusé</span>
                                                @break
                                            @default
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($demande->statut) }}</span>
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $demande->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('demandes.show', $demande) }}" class="text-blue-600 hover:text-blue-800">Voir</a>
                                        @if($demande->statut === 'en_attente')
                                            <a href="{{ route('demandes.edit', $demande) }}" class="ml-2 text-green-600 hover:text-green-800">Modifier</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune demande</h3>
                    <p class="mt-1 text-sm text-gray-500">Vous n'avez pas encore fait de demandes.</p>
                    <div class="mt-6">
                        <a href="{{ route('demandes.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Nouvelle Demande
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Contenu onglet Toutes les demandes -->
        <div id="toutes-demandes" class="tab-content p-6 hidden">
            <h2 class="text-xl font-semibold mb-4">Toutes les Demandes - {{ $departementResponsable }}</h2>
            
            @if($toutesLesDemandes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employé</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Début</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Fin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Demande</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($toutesLesDemandes as $demande)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $demande->user->prenom ?? '' }} {{ $demande->user->nom ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $demande->type === 'conge' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($demande->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $demande->date_debut_formatee }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $demande->date_fin_formatee }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $demande->nombre_jours }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @switch($demande->statut)
                                            @case('en_attente')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                                                @break
                                            @case('approuve')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approuvé</span>
                                                @break
                                            @case('rejete')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Refusé</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $demande->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('demandes.show', $demande) }}" class="text-blue-600 hover:text-blue-800">Voir</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-blue-50 p-4 rounded-lg">
        <h3 class="font-semibold text-blue-800">Congés Annuels</h3>
        <p class="text-2xl font-bold">{{ Auth::user()->conge_annuel_restant }}/30 jours</p>
        <p class="text-sm text-blue-600">Août-Septembre seulement</p>
    </div>
    
    <div class="bg-green-50 p-4 rounded-lg">
        <h3 class="font-semibold text-green-800">Absences</h3>
        <p class="text-2xl font-bold">{{ Auth::user()->absence_restante }}/10 jours</p>
    </div>
    
    <div class="bg-purple-50 p-4 rounded-lg">
        <h3 class="font-semibold text-purple-800">Maternité</h3>
        <p class="text-2xl font-bold">{{ Auth::user()->maternite_restant }}/98 jours</p>
    </div>
</div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">Aucune demande dans votre département.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Liens rapides -->
    
        <a href="{{ route('demandes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
            ➕ Nouvelle Demande
        </a>
    </div>
</div>

<script>
// Fonction de validation des quotas
function validateQuota() {
    const type = document.getElementById('type').value;
    const dateDebut = document.getElementById('date_debut').value;
    const dateFin = document.getElementById('date_fin').value;
    
    if (!dateDebut || !dateFin) return true;
    
    const debut = new Date(dateDebut);
    const fin = new Date(dateFin);
    const jours = Math.ceil((fin - debut) / (1000 * 60 * 60 * 24)) + 1;
    
    if (type === 'conge_annuel') {
        const month = debut.getMonth() + 1;
        if (month !== 8 && month !== 9) {
            alert('Les congés annuels ne sont autorisés qu\'en août et septembre');
            return false;
        }
    }
    
    return true;
}

// Gestion des onglets
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    // Afficher le premier onglet par défaut
    if (tabContents.length > 0) {
        tabContents[0].classList.remove('hidden');
    }

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');

            // Réinitialiser tous les onglets
            tabButtons.forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Activer l'onglet sélectionné
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('border-blue-500', 'text-blue-600');
            
            const targetElement = document.getElementById(targetTab);
            if (targetElement) {
                targetElement.classList.remove('hidden');
            }
        });
    });
});

</script>
@endsection