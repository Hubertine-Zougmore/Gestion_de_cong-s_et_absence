@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-10">
    <!-- En-tête avec navigation -->
    <div class="mb-8">
        <nav class="flex items-center text-sm text-gray-600 mb-4">
            <a href="{{ route('admin.parametres.index') }}" class="hover:text-blue-600 transition-colors">
                Gestion des paramètres
            </a>
            <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-900 font-medium">Ajouter un paramètre</span>
        </nav>
        
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Nouveau paramètre</h2>
                <p class="text-gray-600 mt-1">Configurez un nouveau paramètre système pour votre application</p>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.parametres.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Champ Nom -->
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                    Nom du paramètre
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nom"
                       name="nom" 
                       value="{{ old('nom') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nom') border-red-500 @enderror"
                       placeholder="Ex: app_name, max_upload_size..."
                       required>
                @error('nom')
                    <div class="mt-1 flex items-center text-red-600 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Champ Valeur -->
            <div>
                <label for="valeur" class="block text-sm font-medium text-gray-700 mb-2">
                    Valeur
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="valeur"
                       name="valeur" 
                       value="{{ old('valeur') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('valeur') border-red-500 @enderror"
                       placeholder="Ex: Mon Application, 10MB, true..."
                       required>
                @error('valeur')
                    <div class="mt-1 flex items-center text-red-600 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Champ Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                    <span class="text-gray-400 text-xs">(Optionnel)</span>
                </label>
                <textarea id="description"
                          name="description" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                          placeholder="Décrivez l'utilité de ce paramètre...">{{ old('description') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">
                    Ajoutez une description pour aider les autres administrateurs à comprendre l'utilité de ce paramètre.
                </p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Les champs marqués d'un <span class="text-red-500">*</span> sont obligatoires
                </div>
                
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.parametres.index') }}" 
                       class="px-4 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Annuler
                    </a>
                    
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Enregistrer le paramètre
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Conseils -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="text-sm font-medium text-blue-800 mb-1">Conseils pour les paramètres</h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Utilisez des noms explicites et en snake_case (ex: max_file_size)</li>
                    <li>• Les valeurs peuvent être du texte, des nombres ou des booléens (true/false)</li>
                    <li>• Une description claire aide les autres administrateurs</li>
                    <li>• Évitez de modifier les paramètres système critiques</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection