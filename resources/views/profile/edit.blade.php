@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-white shadow rounded-lg">
        <!-- En-tête -->
        <div class="px-6 py-4 border-b">
            <h2 class="text-2xl font-bold text-gray-800">Modifier le Profil</h2>
            <p class="text-gray-600">Mettez à jour vos informations personnelles</p>
        </div>

        <div class="p-6">
            @if (session('status') === 'profile-updated')
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    Profil mis à jour avec succès !
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <!-- Photo de profil -->
               {{-- <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Photo de profil</label>
                    <div class="flex items-center">
                        <div class="mr-4">
                            @if(auth()->user()->photo)
                                <img src="{{ auth()->user()->photo_url }}" 
                                     alt="Photo actuelle" 
                                     class="w-20 h-20 rounded-full object-cover">
                            @else
                                <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xl font-bold">
                                        {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <input type="file" name="photo" 
                                   class="block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-full file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-blue-50 file:text-blue-700
                                          hover:file:bg-blue-100">
                            @error('photo')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            
                            @if(auth()->user()->photo)
                                <label class="flex items-center mt-2">
                                    <input type="checkbox" name="remove_photo" value="1" class="mr-2">
                                    <span class="text-sm text-gray-600">Supprimer la photo actuelle</span>
                                </label>
                            @endif
                        </div>
                    </div>
                </div>  --}}

                <!-- Informations personnelles -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                        <input type="text" name="nom" id="nom" 
                               value="{{ old('nom', auth()->user()->nom) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('nom')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                        <input type="text" name="prenom" id="prenom" 
                               value="{{ old('prenom', auth()->user()->prenom) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('prenom')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email et Téléphone -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" 
                               value="{{ old('email', auth()->user()->email) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-700">Numéro de téléphone</label>
                        <input type="tel" name="telephone" id="telephone" 
                               value="{{ old('telephone', auth()->user()->telephone) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ex: +226 70 12 34 56">
                        @error('telephone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informations professionnelles -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="matricule" class="block text-sm font-medium text-gray-700">Matricule</label>
                        <input type="text" name="matricule" id="matricule" 
                               value="{{ old('matricule', auth()->user()->matricule) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('matricule')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
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
                        <label for="date_embauche" class="block text-sm font-medium text-gray-700">Date d'embauche</label>
                        <input type="date" name="date_embauche" id="date_embauche" 
                               value="{{ old('date_embauche', auth()->user()->date_embauche ? auth()->user()->date_embauche->format('Y-m-d') : '') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('date_embauche')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-between items-center">
                    <a href="{{ route('profile.show') }}" 
                       class="text-gray-600 hover:text-gray-800 underline">
                        Retour au profil
                    </a>
                    
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>

            <!-- Séparation -->
            <div class="border-t my-8"></div>

            <!-- Suppression du compte -->
            <div class="bg-red-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-red-800 mb-2">Zone dangereuse</h3>
                <p class="text-red-600 mb-4">Une fois votre compte supprimé, toutes ses ressources et données seront effacées définitivement.</p>
                
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    
                    <button type="submit" 
                            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')">
                        Supprimer mon compte
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection