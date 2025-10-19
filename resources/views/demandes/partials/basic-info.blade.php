{{-- resources/views/demandes/partials/basic-info.blade.php --}}
<div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Informations générales
    </h3>
    
    <dl class="space-y-4">
        <div class="flex justify-between items-start">
            <dt class="text-sm font-medium text-gray-500">Employé</dt>
            <dd class="text-sm text-gray-900 font-medium">{{ $demande?->user?->prenom }} {{ $demande?->user?->nom }}</dd>
        </div>
        
       <div class="flex justify-between items-start">
    <dt class="text-sm font-medium text-gray-500">Direction</dt>
    <dd class="text-sm text-gray-900">
        @if($demande->user && $demande->user->direction)
            {{ $demande->user->direction->nom ?? 'Non spécifié' }}
            @if($demande->user->direction->code ?? false)
                ({{ $demande->user->direction->code }})
            @endif
        @else
            Non spécifié
        @endif
    </dd>
</div>
        <div class="flex justify-between items-start">
            <dt class="text-sm font-medium text-gray-500">Type de demande</dt>
            <dd class="text-sm">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $demande->type === 'conge' ? 'bg-green-100 text-green-800' : 
                       ($demande->type === 'permission' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                    {{ ucfirst($demande->type) }}
                </span>
            </dd>
        </div>
        
        <div class="flex justify-between items-start">
            <dt class="text-sm font-medium text-gray-500">Durée</dt>
            <dd class="text-sm text-gray-900 font-semibold">
                {{ $demande->nombre_jours }} jour{{ $demande->nombre_jours > 1 ? 's' : '' }}
            </dd>
        </div>
        
        <div class="border-t border-gray-100 pt-4">
            <div class="flex justify-between items-start">
                <dt class="text-sm font-medium text-gray-500">Soumise le</dt>
                <dd class="text-sm text-gray-900">{{ $demande?->created_at?->format('d/m/Y à H:i') }}</dd>
            </div>
            
            @if($demande?->updated_at?->ne($demande->created_at))
                <div class="flex justify-between items-start mt-2">
                    <dt class="text-sm font-medium text-gray-500">Dernière modification</dt>
                    <dd class="text-sm text-gray-900">{{ $demande?->updated_at?->format('d/m/Y à H:i') }}</dd>
                </div>
            @endif
        </div>
    </dl>
</div>