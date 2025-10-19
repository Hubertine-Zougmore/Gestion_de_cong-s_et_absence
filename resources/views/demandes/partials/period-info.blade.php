{{-- resources/views/demandes/partials/period-info.blade.php --}}
<div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        Période demandée
    </h3>
    
    <div class="space-y-4">
        <!-- Dates de début et fin -->
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="text-center">
                <div class="text-xs text-gray-500 mb-1">Date de début</div>
                <div class="text-lg font-bold text-gray-900">
                    {{ $demande?->date_debut?->format('d') }}
                </div>
                <div class="text-sm text-gray-600">
                    {{ $demande?->date_debut?->format('M Y') }}
                </div>
                <div class="text-xs text-gray-500">
                  {{ $demande?->date_debut?->format('l j F Y') }}
                </div>
            </div>
            
            <div class="flex flex-col items-center px-4">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
                <div class="text-xs text-gray-500 mt-1">
                    {{ $demande?->date_debut?->diffInDays($demande?->date_fin) + 1 }} jours
                </div>
            </div>
            
            <div class="text-center">
                <div class="text-xs text-gray-500 mb-1">Date de fin</div>
                <div class="text-lg font-bold text-gray-900">
                    {{ $demande?->date_fin?->format('d') }}
                </div>
                <div class="text-sm text-gray-600">
                    {{ $demande?->date_fin?->format('M Y') }}
                </div>
                <div class="text-xs text-gray-500">
                   {{ $demande?->date_debut?->format('l j F Y') }}
                </div>
            </div>
        </div>
        
        <!-- Informations additionnelles -->
        <div class="grid grid-cols-2 gap-3">
            <div class="text-center p-3 bg-blue-50 rounded-lg">
                <div class="text-lg font-bold text-blue-600">{{ $demande?->nombre_jours }}</div>
                <div class="text-xs text-blue-600">Jours ouvrables</div>
            </div>
            
            <div class="text-center p-3 bg-green-50 rounded-lg">
                <div class="text-lg font-bold text-green-600">
                    {{ $demande?->date_debut?->diffInDays($demande?->date_fin) + 1 }}
                </div>
                <div class="text-xs text-green-600">Jours calendaires</div>
            </div>
        </div>
        
        <!-- Statut temporel -->
        @php
            $now = now();
            $daysUntilStart = $now->diffInDays($demande?->date_debut, false);
        @endphp
        
        <div class="p-3 rounded-lg 
            {{ $daysUntilStart > 0 ? 'bg-blue-50 border border-blue-200' : 
               ($demande->date_fin->isPast() ? 'bg-gray-50 border border-gray-200' : 'bg-yellow-50 border border-yellow-200') }}">
            <div class="text-sm font-medium text-center
                {{ $daysUntilStart > 0 ? 'text-blue-800' : 
                   ($demande->date_fin->isPast() ? 'text-gray-800' : 'text-yellow-800') }}">
                @if($daysUntilStart > 0)
                    Commence dans {{ abs($daysUntilStart) }} jour{{ abs($daysUntilStart) > 1 ? 's' : '' }}
                @elseif($demande->date_fin->isPast())
                    Période terminée
                @elseif($demande->date_debut->isPast() && $demande->date_fin->isFuture())
                    En cours
                @else
                    Commence aujourd'hui
                @endif
            </div>
        </div>
    </div>
</div>