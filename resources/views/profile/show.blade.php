@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-white shadow rounded-lg">
        <!-- En-tête -->
        <div class="px-6 py-4 border-b">
            <h2 class="text-2xl font-bold text-gray-800">Mon Profil</h2>
        </div>

        <div class="p-6">
            <div class="flex items-center mb-6">
                <!-- Conteneur photo avec taille fixe -->
                <div class="relative w-24 h-24">
                    @if($user->photo)
                        <img src="{{ $user->photo_url }}" 
                             alt="Photo de profil"
                             class="w-full h-full rounded-full object-cover border-4 border-white shadow transition-opacity duration-300">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow">
                            <span class="text-white text-3xl font-bold">
                                {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    
                    <!-- Badge de statut -->
                    <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 rounded-full border-2 border-white"></div>
                </div>
                
                <!-- Informations utilisateur -->
                <div class="ml-6">
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $user->prenom }} {{ $user->nom }}
                    </h1>
                    
                    <div class="mt-2 space-y-1">
                        <p class="text-gray-600 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            {{ $user->email }}
                        </p>
                        
                        @if($user->telephone)
                            <p class="text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                                {{ $user->telephone }}
                            </p>
                        @endif
                        
                        <p class="text-sm text-gray-500 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Membre depuis {{ $user->created_at->format('d/m/Y') }}
                        </p>
                        
                        @if($user->matricule)
                            <p class="text-sm text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Matricule: {{ $user->matricule }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations détaillées -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b border-gray-200 pb-2">
                        Informations Personnelles
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Nom :</span>
                            <span class="text-gray-900">{{ $user->nom }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Prénom :</span>
                            <span class="text-gray-900">{{ $user->prenom }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Email :</span>
                            <span class="text-gray-900">{{ $user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Téléphone :</span>
                            <span class="text-gray-900">{{ $user->telephone ?? 'Non renseigné' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Matricule :</span>
                            <span class="text-gray-900">{{ $user->matricule ?? 'Non renseigné' }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b border-gray-200 pb-2">
                        Informations Professionnelles
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Direction :</span>
                            <span class="text-gray-900">{{ $user->direction ?? 'Non renseignée' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Poste :</span>
                            <span class="text-gray-900">{{ $user->poste ?? 'Non renseigné' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Date d'embauche :</span>
                            <span class="text-gray-900">
                                {{ $user->date_embauche ? $user->date_embauche->format('d/m/Y') : 'Non renseignée' }}
                            </span>
                        </div>
                        @if($user->solde_conges !== null)
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Solde congés :</span>
                                <span class="text-green-600 font-semibold">{{ $user->solde_conges }} jours</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('profile.edit') }}" 
                   class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                    Modifier le profil
                </a>
                
                @if($user->solde_conges !== null)
                    <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-3 rounded-lg flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">Solde de congés : {{ $user->solde_conges }} jours</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection