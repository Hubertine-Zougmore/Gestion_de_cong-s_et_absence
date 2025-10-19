{{-- resources/views/demandes/partials/header.blade.php --}}
<div class="bg-white shadow-sm rounded-lg p-6 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <nav class="text-sm text-gray-500 mb-2">
                <a href="{{ route('agent.dashboard') }}" class="hover:text-gray-700 transition-colors">Tableau de bord</a>
                <span class="mx-2">›</span>
                <a href="{{ route('demandes.index') }}" class="hover:text-gray-700 transition-colors">Mes demandes</a>
                <span class="mx-2">›</span>
               /<span class="text-gray-900 font-medium">Demande {{ $demande->id }}</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900">Demande {{ $demande->id }}</h1>
            <p class="text-gray-600 mt-1">
                <span class="font-medium">{{ $demande->user->prenom ?? ''}} {{ $demande->user->nom ?? ''}}</span>
                • Soumise le {{ $demande->created_at != null ? $demande->created_at->format('d/m/Y à H:i') : ''}}
            </p>
        </div>
        <div class="flex items-center space-x-4">
            {{-- Status Badge inline au lieu d'include --}}
            @switch($demande->statut)
                @case('en_attente')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        En attente
                    </span>
                    @break
                @case('approuve')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Approuvé
                    </span>
                    @break
                @case('rejete')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Rejeteé
                    </span>
                    @break
            @endswitch
            
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour
            </a>
        </div>
    </div>
</div>