{{-- resources/views/demandes/partials/content.blade.php --}}
@if($demande->motif || $demande->commentaire_drh)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <!-- Motif de la demande -->
        @if($demande->motif)
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Motif de la demande
                </h3>
                <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-400">
                    <p class="text-gray-900 leading-relaxed text-sm">{{ $demande->motif }}</p>
                </div>
                <div class="mt-3 text-xs text-gray-500">
                    Fourni par {{ $demande->user->prenom }} {{ $demande->user->nom }}
                </div>
            </div>
        @endif
        
        <!-- Commentaire DRH -->
        @if($demande->commentaire_drh)
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    Commentaire DRH
                </h3>
                <div class="bg-green-50 border-l-4 border-green-400 rounded-lg p-4">
                    <p class="text-gray-900 leading-relaxed text-sm">{{ $demande->commentaire_drh }}</p>
                </div>
                <div class="mt-3 text-xs text-gray-500 flex items-center justify-between">
                    <span>Commentaire des ressources humaines</span>
                    @if($demande->updated_at->ne($demande->created_at))
                        <span>{{ $demande->updated_at->format('d/m/Y à H:i') }}</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
    
    <!-- Section complète si les deux existent -->
    @if($demande->motif && $demande->commentaire_drh)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm">
                    <div class="text-blue-800 font-medium">Échange complet</div>
                    <div class="text-blue-700 mt-1">
                        Cette demande contient à la fois la justification de l'employé et la réponse de la DRH.
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif

<!-- Section vide si aucun contenu -->
@if(!$demande->motif && !$demande->commentaire_drh)
    <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg p-8 mb-8">
        <div class="text-center">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <div class="text-gray-500 text-sm">
                <div class="font-medium">Aucun motif ou commentaire</div>
                <div class="mt-1">Cette demande ne contient pas de justification détaillée.</div>
            </div>
        </div>
    </div>
@endif