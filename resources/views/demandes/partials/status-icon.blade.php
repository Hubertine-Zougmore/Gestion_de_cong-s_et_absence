{{-- resources/views/demandes/partials/status-icon.blade.php --}}
@php
    $currentStatus = $statut ?? $status ?? 'inconnu';
@endphp

@switch($currentStatus)
    @case('en_attente')
        <div class="w-16 h-16 mx-auto rounded-full bg-yellow-100 flex items-center justify-center border-4 border-yellow-200 shadow-sm">
            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        @break
    @case('approuve')
        <div class="w-16 h-16 mx-auto rounded-full bg-green-100 flex items-center justify-center border-4 border-green-200 shadow-sm">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        @break
    @case('rejete')
        <div class="w-16 h-16 mx-auto rounded-full bg-red-100 flex items-center justify-center border-4 border-red-200 shadow-sm">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        @break
    @default
        <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center border-4 border-gray-200 shadow-sm">
            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
@endswitch