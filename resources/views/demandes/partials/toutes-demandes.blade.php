@if($demandes->count() > 0)
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Toutes les demandes</h3>
            
            <div class="space-y-4">
                @foreach($demandes as $demande)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold">{{ $demande->user->name }}</h4>
                                <p class="text-sm text-gray-600">Du {{ $demande->date_debut->format('d/m/Y') }} au {{ $demande->date_fin->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-600">{{ $demande->nombre_jours ?? $demande->date_debut->diffInDays($demande->date_fin) + 1 }} jours</p>
                            </div>
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded 
                                @if($demande->statut === 'en_attente') bg-yellow-100 text-yellow-800
                                @elseif($demande->statut === 'approuve') bg-green-100 text-green-800
                                @elseif($demande->statut === 'rejete') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $demande->statut === 'en_attente' ? 'En attente' : 
                                   ($demande->statut === 'approuve' ? 'Approuvée' : 
                                   ($demande->statut === 'rejete' ? 'Rejetée' : $demande->statut)) }}
                            </span>
                        </div>
                        
                        @if($demande->statut !== 'en_attente')
                            <div class="mt-2 text-xs text-gray-500">
                                Traité par: {{ $demande->traite_par_user->name ?? 'Système' }}
                                le {{ $demande->traite_le->format('d/m/Y H:i') }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $demandes->appends(['onglet' => 'toutes'])->links() }}
            </div>
        </div>
    </div>
@else
    <div class="bg-white shadow rounded-lg p-6 text-center">
        <p class="text-gray-500">Aucune demande trouvée.</p>
    </div>
@endif