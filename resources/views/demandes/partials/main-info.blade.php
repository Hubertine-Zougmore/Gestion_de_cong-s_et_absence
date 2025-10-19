{{-- resources/views/demandes/partials/main-info.blade.php --}}
<div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white mb-8 shadow-lg">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-8">
            <!-- Type de demande -->
            <div class="text-center">
                <div class="text-4xl mb-2">
                    @switch($demande->type)
                        @case('conge')
                            🏖️
                            @break
                        @case('permission')
                            📋
                            @break
                        @case('maladie')
                            🏥
                            @break
                        @default
                            ⏰
                    @endswitch
                </div>
                <div class="text-lg font-semibold text-blue-100">{{ ucfirst($demande->type) }}</div>
            </div>
            
            <!-- Séparateur -->
            <div class="w-px h-16 bg-blue-400"></div>
            
            <!-- Durée -->
            <div class="text-center">
                <div class="text-5xl font-bold text-white">{{ $demande->nombre_jours }}</div>
                <div class="text-blue-100 text-sm font-medium">
                    jour{{ $demande->nombre_jours > 1 ? 's' : '' }}
                </div>
            </div>
        </div>
        
        <!-- Période -->
        <div class="text-right">
            <div class="text-blue-100 text-sm font-medium mb-1">Période demandée</div>
            <div class="text-2xl font-bold text-white">
                {{ $demande?->date_debut?->format('d/m') }} - {{ $demande?->date_fin?->format('d/m/Y') }}
            </div>
            <div class="text-blue-100 text-sm mt-1">
                {{ $demande?->date_debut?->diffInDays($demande?->date_fin) + 1 }} jour(s) calendaires
            </div>
        </div>
    </div>
</div>