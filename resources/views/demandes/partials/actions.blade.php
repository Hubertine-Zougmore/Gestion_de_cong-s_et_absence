{{-- resources/views/demandes/partials/actions.blade.php --}}
<div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        Statut et Actions
    </h3>
    
    <!-- Statut visuel central -->
    <div class="text-center mb-6">
        @include('demandes.partials.status-icon', ['statut' => $demande->statut])
        <div class="mt-2">
            @include('demandes.partials.status-badge', ['statut' => $demande->statut])
        </div>
    </div>
    
    <!-- Actions DRH seulement si ce n'est PAS sa propre demande -->
    @if(Auth::user()->role === 'drh' && $demande->statut === 'en_attente')
        @if($demande->user_id !== Auth::id())
            <div class="space-y-3">
                <div class="text-center text-sm text-gray-600 mb-4">
                    Actions DRH disponibles
                </div>
                
                <form method="POST" action="{{ route('drh.demande.approuver', $demande) }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-all duration-200 font-medium shadow-sm"
                            onclick="return confirm('Êtes-vous sûr de vouloir approuver cette demande ?')">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Approuver la demande
                    </button>
                </form>
                
                <form method="POST" action="{{ route('drh.demande.rejeter', $demande) }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200 transition-all duration-200 font-medium shadow-sm"
                            onclick="return confirm('Êtes-vous sûr de vouloir rejeter cette demande ?')">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Rejeter la demande
                    </button>
                </form>
            </div>
        @else
            <!-- Message d'alerte pour le DRH qui voit sa propre demande -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                <div class="flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span class="text-yellow-800 font-medium">Action non autorisée</span>
                </div>
                <p class="text-yellow-700 text-sm">
                    Vous ne pouvez pas traiter vos propres demandes pour des raisons de conformité.
                </p>
                <p class="text-yellow-600 text-xs mt-1">
                    Contactez un autre membre de la DRH pour le traitement de cette demande.
                </p>
            </div>
        @endif
    @endif
    
    <!-- Actions pour l'employé propriétaire -->
    @if($demande->user_id === Auth::id() && $demande->statut === 'en_attente')
        <div class="space-y-3 mt-4">
            <div class="text-center text-sm text-gray-600 mb-4">
                Gérer ma demande
            </div>
            
            @if(Route::has('demandes.edit'))
                <a href="{{ route('demandes.edit', $demande) }}" 
                   class="block w-full text-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-200 font-medium shadow-sm">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier ma demande
                </a>
            @endif
            
            @if(Route::has('demandes.destroy'))
                <form method="POST" action="{{ route('demandes.destroy', $demande) }}" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette demande ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200 transition-all duration-200 font-medium shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Supprimer la demande
                    </button>
                </form>
            @endif
        </div>
    @endif
    
    <!-- Statut final pour demandes traitées -->
   {{-- Statut avec double validation pour congés annuels --}}
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Statut et Actions</h3>
    
    @if($demande->type === 'conge_annuel' && $demande->statut === 'approuve')
        {{-- Congé annuel approuvé : vérifier le statut de jouissance --}}
        @if($demande->demandeJouissance)
            @if($demande->demandeJouissance->statut === 'en_attente')
                <div class="bg-orange-100 border-l-4 border-orange-500 p-4 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-orange-500 mr-2"></i>
                        <div>
                            <p class="text-orange-800 font-medium">Congé approuvé</p>
                            <p class="text-orange-700 text-sm">En attente d'autorisation de jouissance par le responsable hiérarchique</p>
                        </div>
                    </div>
                </div>
            @elseif($demande->demandeJouissance->statut === 'approuve')
                <div class="bg-green-100 border-l-4 border-green-500 p-4 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <div>
                            <p class="text-green-800 font-medium">Autorisé</p>
                            <p class="text-green-700 text-sm">Vous pouvez prendre votre congé</p>
                        </div>
                    </div>
                </div>
            @elseif($demande->demandeJouissance->statut === 'rejete')
                <div class="bg-red-100 border-l-4 border-red-500 p-4 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle text-red-500 mr-2"></i>
                        <div>
                            <p class="text-red-800 font-medium">Jouissance refusée</p>
                            <p class="text-red-700 text-sm">Congé non autorisé malgré l'approbation</p>
                            @if($demande->demandeJouissance->commentaire_responsable)
                                <p class="text-red-600 text-xs mt-1">
                                    Motif: {{ $demande->demandeJouissance->commentaire_responsable }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-4">
                <div class="flex items-center">
                    <i class="fas fa-cog fa-spin text-blue-500 mr-2"></i>
                    <div>
                        <p class="text-blue-800 font-medium">Congé approuvé</p>
                        <p class="text-blue-700 text-sm">Demande de jouissance en cours de création...</p>
                    </div>
                </div>
            </div>
        @endif
    @else
        {{-- Pour les autres types de congés ou statuts --}}
        @if($demande->statut === 'approuve')
            <div class="bg-green-100 border-l-4 border-green-500 p-4 mb-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <p class="text-green-800 font-medium">Demande approuvée</p>
                </div>
            </div>
        @elseif($demande->statut === 'rejete')
            <div class="bg-red-100 border-l-4 border-red-500 p-4 mb-4">
                <div class="flex items-center">
                    <i class="fas fa-times-circle text-red-500 mr-2"></i>
                    <div>
                        <p class="text-red-800 font-medium">Demande rejetée</p>
                        @if($demande->commentaire_drh)
                            <p class="text-red-700 text-sm mt-1">{{ $demande->commentaire_drh }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 mb-4">
                <div class="flex items-center">
                    <i class="fas fa-clock text-yellow-500 mr-2"></i>
                    <p class="text-yellow-800 font-medium">En attente de traitement</p>
                </div>
            </div>
        @endif
    @endif

    {{-- Actions selon les droits --}}
    @if($showApproveReject && $demande->statut === 'en_attente')
        <div class="flex space-x-3">
            <button onclick="approuverDemande({{ $demande->id }})" 
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                <i class="fas fa-check mr-1"></i> Approuver
            </button>
            <button onclick="rejeterDemande({{ $demande->id }})" 
                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                <i class="fas fa-times mr-1"></i> Rejeter
            </button>
        </div>
    @endif

    @if($showEditDelete && $demande->statut === 'en_attente')
        <div class="flex space-x-3">
            <a href="{{ route('demandes.edit', $demande) }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-edit mr-1"></i> Modifier
            </a>
        </div>
    @endif
</div>
   
    <!-- Actions universelles -->
    <div class="border-t border-gray-100 pt-4 mt-4">
        <a href="{{ route('demandes.index') }}" 
           class="block w-full text-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
            Retour à mes demandes
        </a>
    </div>
</div>