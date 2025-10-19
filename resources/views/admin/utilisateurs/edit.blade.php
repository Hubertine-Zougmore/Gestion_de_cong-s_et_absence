@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Bienvenue, {{ Auth::user()->prenom }} {{ Auth::user()->nom }}</h1>
    <p class="text-gray-600 mb-6">Vous êtes connecté en tant qu'<strong>Administrateur</strong>.</p>

    <div class="max-w-4xl mx-auto bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Modifier l'utilisateur</h1>
                <a href="{{ route('admin.utilisateurs.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    <h2 class="font-bold mb-2">Erreurs de validation</h2>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(isset($utilisateur) && $utilisateur->id)
            <form method="POST" action="{{ route('admin.utilisateurs.update', $utilisateur->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                        <input type="text" name="nom" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               value="{{ old('nom', $utilisateur->nom) }}" required>
                        @error('nom')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                        <input type="text" name="prenom" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               value="{{ old('prenom', $utilisateur->prenom) }}" required>
                        @error('prenom')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           value="{{ old('email', $utilisateur->email) }}" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Matricule</label>
                        <input type="text" name="matricule" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               value="{{ old('matricule', $utilisateur->matricule) }}">
                        @error('matricule')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                 
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Direction</label>
                         <select name="direction" id="direction" class="form-control mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
                            <option value="">Veuillez selectionner une direction</option>
                            <option value="presidence">Présidence</option>
                            <option value="DSI">DSI</option>
                            <option value="AC">Agence Comptable</option>
                            <option value="ACM">Atelier Centrale de Maintenance</option>
                            <option value="BUC">Bibliothèque Universitaire Centrale </option>
                            <option value="CA">Conseil d'Administration</option>
                            <option value="CFVU">Conseil de la Formation et de Vie Universitaire </option>
                            <option value="CS">Conseil Scientifique </option>
                            <option value="DFPC">Direction de la Formation Professionnelle et Continue </option>
                            <option value="DIP">Direction des Innovations Pedagogique</option>
                            <option value="DPE">Direction de la Promotion des Enseignants </option>
                            <option value="DPU">Direction de la Presse Universitaire</option>
                            <option value="DR">Direction de la Recherche </option>
                            <option value="DRH">Direction des Ressources Humaines</option>
                            <option value="CU">Centre Universitaire </option>
                            <option value="DAF">Direction de l'Administration et des Finances</option>
                            <option value="DAOI">Direction des Affaires académiques, de l'Orientation et de l'Information</option>
                           <option value="DCU">Direction de la Coopération Universitaire</option>
                           <option value="DEC">Direction des Etudes et De la consultation</option>
                          <option value="DEP">Direction des Etudes et la Planification </option>
                         <option value="IFOAD">Institut de Formation Ouverte et A Distance</option>
                        <option value="IUFIC">Institut Universitaire de Formations Initiales et Continues</option>
                         <option value="UFR/SJP">Unité de Formation et de Recherche en Sciences Juridique et Politique</option>
                         <option value="UFR/ST">Unité de Formation et de Recherche en Sciences et Techniques</option>
                         <option value="UFR/SEG">Unité de Formation et de Recherche en Sciences Economiques et de Gestion</option>
                        </select>
                        
                        @error('direction')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Poste</label>
                        <select name="poste" id="poste" class="form-control mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
                            <option value="">Veuillez selectionner un poste</option>
                            <option value="directeur">Directeur</option>
                            <option value="agent">Agent</option>
                        </select>
                        
                        @error('poste')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
    <!-- Ajoutez ce champ dans votre formulaire de création -->
<div>
    <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
    <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}"
           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2"
           placeholder="+226 70 12 34 56">
    @error('telephone')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Sexe</label>
    <select name="sexe" id="sexe" class="form-control mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
        <option value="">Veuillez selectionner un sexe</option>
        <option value="masculin">Masculin</option>
        <option value="feminin">Féminin</option>
    </select>
     @error('sexe')
         <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

    <div>
        
    <label class="block text-sm font-medium text-gray-700 mb-2">Rôle *</label>
    <select name="role" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <option value="">Sélectionner un rôle</option>
         @foreach(App\Models\User::getRolesList() as $key => $label)
        <option value="{{ $key }}" {{ old('role', $user->role ?? '') == $key ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
    </select>
    @error('role')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
     </div>
                 <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date d'embauche</label>
                        <input type="date" name="date_embauche" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               value="{{ old('date_embauche', $utilisateur->date_embauche ? $utilisateur->date_embauche->format('Y-m-d') : '') }}">
                        @error('date_embauche')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <input type="hidden" name="is_active" value="0">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                               {{ old('is_active', $utilisateur->is_active) ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700">Compte activé</span>
                    </label>
                    @error('is_active')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.utilisateurs.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Mettre à jour
                    </button>
                </div>
            </form>
            @else
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <p class="font-bold">Erreur:</p>
                    <p>Utilisateur non trouvé ou ID manquant.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Ajout des icônes Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
@endsection