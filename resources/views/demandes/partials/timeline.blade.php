{{-- resources/views/demandes/partials/timeline.blade.php --}}
<div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Historique de la demande
    </h3>
    
    <div class="flow-root">
        <ul class="-mb-8">
            <!-- Événement: Soumission -->
            <li>
                <div class="relative pb-8">
                    @if($demande->statut !== 'en_attente')
                        <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                    @endif
                    <div class="relative flex space-x-3">
                        <div>
                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Demande soumise</p>
                                <p class="text-sm text-gray-500">
                                    Par {{ $demande?->user?->prenom }} {{ $demande?->user?->nom }}
                                    @if($demande?->motif)
                                        
                                    @endif
                                </p>
                                
                                <!-- AFFICHAGE DU JUSTIFICATIF -->
                                @if($demande?->justificatif)
                                <div class="mt-2 p-2 bg-blue-50 rounded border border-blue-100">
                                    <p class="text-xs font-medium text-blue-800 mb-1">Justificatif fourni :</p>
                                    <div class="flex items-center space-x-2">
                                        @if(in_array(pathinfo($demande?->justificatif, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        @endif
                                        
                                        <span class="text-xs text-blue-600 truncate">
                                            {{ basename($demande->justificatif) }}
                                        </span>
                                        
                                        <div class="flex space-x-1">
                                            <a href="{{ route('demandes.viewJustificatif', $demande->id) }}" 
                                               target="_blank"
                                               class="text-blue-600 hover:text-blue-800 text-xs"
                                               title="Voir le justificatif">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('demandes.downloadJustificatif', $demande->id) }}" 
                                               class="text-green-600 hover:text-green-800 text-xs"
                                               title="Télécharger le justificatif">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    
                                    @if($demande->type_justificatif)
                                    <p class="text-xs text-gray-500 mt-1">
                                        Type: <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $demande->type_justificatif)) }}</span>
                                    </p>
                                    @endif
                                </div>
                                @endif
                                <!-- FIN AFFICHAGE DU JUSTIFICATIF -->
                                
                            </div>
                            <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                <time datetime="{{ $demande?->created_at?->toISOString() }}">
                                    {{ $demande?->created_at?->format('d/m/Y') }}
                                </time>
                                <p class="text-xs text-gray-400">{{ $demande?->created_at?->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            
            <!-- Modifications éventuelles -->
            @if($demande?->updated_at?->ne($demande?->created_at) && $demande?->statut === 'en_attente')
                <li>
                    <div class="relative pb-8">
                        <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Demande modifiée</p>
                                    <p class="text-sm text-gray-500">Mise à jour par l'employé</p>
                                </div>
                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                    <time datetime="{{ $demande->updated_at->toISOString() }}">
                                        {{ $demande->updated_at->format('d/m/Y') }}
                                    </time>
                                    <p class="text-xs text-gray-400">{{ $demande->updated_at->format('H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endif
            
            <!-- Événement: Traitement par DRH -->
            @if($demande->statut !== 'en_attente')
                <li>
                    <div class="relative pb-8">
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full {{ $demande->statut === 'approuve' ? 'bg-green-500' : 'bg-red-500' }} flex items-center justify-center ring-8 ring-white">
                                    @if($demande->statut === 'approuve')
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @else
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    @endif
                                </span>
                            </div>
                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        Demande {{ $demande->statut === 'approuve' ? 'approuvée' : 'refusée' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Par la DRH
                                        @if($demande?->commentaire_drh)
                                            • Avec commentaire
                                        @endif
                                    </p>
                                </div>
                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                    <time datetime="{{ $demande?->updated_at?->toISOString() }}">
                                        {{ $demande?->updated_at?->format('d/m/Y') }}
                                    </time>
                                    <p class="text-xs text-gray-400">{{ $demande?->updated_at?->format('H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endif
        </ul>
    </div>
    
    <!-- Résumé temporel -->
    <div class="mt-6 pt-6 border-t border-gray-100">
        <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center space-x-4">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    @if($demande->statut === 'en_attente')
                        En attente depuis {{ $demande->created_at->diffForHumans() }}
                    @else
                        Traitée en {{ $demande?->created_at?->diffInDays($demande->updated_at) }} jour{{ $demande?->created_at?->diffInDays($demande?->updated_at) > 1 ? 's' : '' }}
                    @endif
                </span>
            </div>
            
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                {{ $demande->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 
                   ($demande->statut === 'approuve' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
            </span>
        </div>
    </div>
</div>