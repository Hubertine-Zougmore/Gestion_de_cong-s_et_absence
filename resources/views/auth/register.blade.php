{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')
@section('title', 'Inscription - Gestion des Congés')
@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            {{-- Section gauche - Informations --}}
            <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-purple-800 animate-gradient rounded-3xl p-8 lg:p-12 text-white relative overflow-hidden">
                {{-- Éléments décoratifs --}}
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full -ml-16 -mb-16"></div>
                
                <div class="relative z-10">
                    <div class="mb-8">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 animate-float">
                            <i class="fas fa-calendar-alt text-3xl"></i>
                        </div>
                        <h1 class="text-4xl lg:text-5xl font-bold mb-4 leading-tight">
                            Rejoignez Notre 
                            <span class="text-yellow-300">Plateforme</span>
                        </h1>
                        <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                            Simplifiez la gestion de vos congés avec notre solution moderne et intuitive
                        </p>
                    </div>
                    
                    {{-- Avantages --}}
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4 group">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-rocket text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Demandes en ligne</h3>
                                <p class="text-blue-100 text-sm">Soumettez vos demandes facilement</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4 group">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-chart-line text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Suivi en temps réel</h3>
                                <p class="text-blue-100 text-sm">Suivez l'état de vos demandes</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4 group">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-shield-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Sécurisé et fiable</h3>
                                <p class="text-blue-100 text-sm">Vos données sont protégées</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4 group">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-mobile-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Interface mobile</h3>
                                <p class="text-blue-100 text-sm">Accessible partout, tout le temps</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section droite - Formulaire d'inscription --}}
            <div class="flex flex-col justify-center">
                <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-12 border border-gray-100">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Créer un compte</h2>
                        <p class="text-gray-600">Rejoignez-nous en quelques minutes</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        {{-- Prénom et Nom --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-blue-500"></i>Prénom
                                </label>
                                <input id="first_name" 
                                       name="first_name" 
                                       type="text" 
                                       value="{{ old('first_name') }}" 
                                       required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('first_name') border-red-500 @enderror"
                                       placeholder="Votre prénom">
                                @error('first_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-blue-500"></i>Nom
                                </label>
                                <input id="last_name" 
                                       name="last_name" 
                                       type="text" 
                                       value="{{ old('last_name') }}" 
                                       required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('last_name') border-red-500 @enderror"
                                       placeholder="Votre nom">
                                @error('last_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-blue-500"></i>Adresse email
                            </label>
                            <input id="email" 
                                   name="email" 
                                   type="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                                   placeholder="votre@email.com">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Téléphone --}}
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone mr-2 text-blue-500"></i>Téléphone
                            </label>
                            <input id="phone" 
                                   name="phone" 
                                   type="tel" 
                                   value="{{ old('phone') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('phone') border-red-500 @enderror"
                                   placeholder="+226 XX XX XX XX">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Département --}}
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-building mr-2 text-blue-500"></i>Département
                            </label>
                            <select id="department" 
                                    name="department" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('department') border-red-500 @enderror">
                                <option value="">Sélectionnez votre département</option>
                                <option value="rh" {{ old('department') == 'rh' ? 'selected' : '' }}>Ressources Humaines</option>
                                <option value="it" {{ old('department') == 'it' ? 'selected' : '' }}>Informatique</option>
                                <option value="finance" {{ old('department') == 'finance' ? 'selected' : '' }}>Finance</option>
                                <option value="marketing" {{ old('department') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="operations" {{ old('department') == 'operations' ? 'selected' : '' }}>Opérations</option>
                                <option value="commercial" {{ old('department') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                            </select>
                            @error('department')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Mot de passe --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-blue-500"></i>Mot de passe
                            </label>
                            <div class="relative">
                                <input id="password" 
                                       name="password" 
                                       type="password" 
                                       required 
                                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror"
                                       placeholder="Choisissez un mot de passe sécurisé">
                                <button type="button" 
                                        onclick="togglePassword('password')" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i id="password-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirmer mot de passe --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-blue-500"></i>Confirmer le mot de passe
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" 
                                       name="password_confirmation" 
                                       type="password" 
                                       required 
                                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Confirmez votre mot de passe">
                                <button type="button" 
                                        onclick="togglePassword('password_confirmation')" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i id="password_confirmation-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Conditions d'utilisation --}}
                        <div class="flex items-start space-x-3">
                            <input id="terms" 
                                   name="terms" 
                                   type="checkbox" 
                                   required 
                                   class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1">
                            <label for="terms" class="text-sm text-gray-600 leading-relaxed">
                                J'accepte les 
                                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">conditions d'utilisation</a>
                                et la 
                                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">politique de confidentialité</a>
                            </label>
                        </div>

                        {{-- Bouton d'inscription --}}
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-xl font-semibold text-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-user-plus mr-2"></i>
                            Créer mon compte
                        </button>

                        {{-- Lien vers connexion --}}
                        <div class="text-center pt-4">
                            <p class="text-gray-600">
                                Vous avez déjà un compte ? 
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold ml-1">
                                    Se connecter
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CSS personnalisé --}}
<style>
@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 6s ease infinite;
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>

{{-- JavaScript pour toggle password --}}
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection