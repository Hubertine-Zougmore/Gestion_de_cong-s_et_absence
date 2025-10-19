<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Université Thomas SANKARA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Inclure les polices Google -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Inclure Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .glass-effect {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="h-full bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Section violette à gauche avec titre -->
        <div class="hidden lg:flex lg:w-1/3 bg-gradient-to-br from-purple-700 to-purple-900 relative overflow-hidden">
            <!-- Effets décoratifs -->
            <div class="absolute top-0 left-0 w-32 h-32 bg-white/10 rounded-full -translate-x-16 -translate-y-16"></div>
            <div class="absolute bottom-0 right-0 w-48 h-48 bg-white/10 rounded-full translate-x-24 translate-y-24"></div>
            <div class="absolute top-1/2 left-0 w-24 h-24 bg-white/5 rounded-full -translate-x-12"></div>
            
            <!-- Contenu centré -->
            <div class="flex flex-col justify-center items-center p-12 text-white relative z-10 w-full text-center">
                <!-- Logo UTS -->
                <div class="w-24 h-24 mx-auto mb-8 bg-white/20 rounded-3xl flex items-center justify-center shadow-2xl glass-effect border border-white/30 p-4">
                    <img src="{{ asset('images/logo-uts.png') }}" alt="Logo UTS" class="w-full h-full object-contain">
                </div>
                
                <!-- Titre principal -->
                <h1 class="text-4xl font-bold mb-4 leading-tight">
                    Université<br>
                    Thomas SANKARA
                </h1>
                
                <!-- Sous-titre -->
                <div class="w-16 h-1 bg-white/50 rounded-full mb-4"></div>
                <p class="text-xl text-purple-100 font-medium mb-2">Portail de connexion</p>
                <p class="text-purple-200 opacity-90 text-sm">Accédez à votre espace personnel</p>
                
                <!-- Éléments décoratifs supplémentaires -->
                <div class="mt-12 flex space-x-2">
                    <div class="w-3 h-3 bg-white/40 rounded-full animate-pulse"></div>
                    <div class="w-3 h-3 bg-white/30 rounded-full animate-pulse delay-100"></div>
                    <div class="w-3 h-3 bg-white/20 rounded-full animate-pulse delay-200"></div>
                </div>
            </div>
        </div>

        <!-- Section principale avec image de fond et formulaire -->
        <div class="flex-1 flex items-center justify-center relative overflow-hidden">
            <!-- Image de fond avec overlay -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/fond-login.jpeg') }}" alt="Background UTS" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 to-purple-900/60"></div>
            </div>

            <!-- Formulaire centré -->
            <div class="relative z-10 w-full max-w-md mx-6">
                <!-- Header mobile -->
                <div class="text-center mb-8 lg:hidden">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-purple-600 to-purple-800 rounded-2xl flex items-center justify-center shadow-lg p-3">
                        <img src="{{ asset('images/logo-uts.png') }}" alt="Logo UTS" class="w-full h-full object-contain">
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Université Thomas SANKARA</h2>
                    <p class="text-purple-100">Portail de connexion</p>
                </div>

                <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20 glass-effect">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Connexion</h3>
                    <p class="text-gray-600 mb-8">Connectez-vous à votre compte</p>

                    <!-- Session Status -->
                    @if (session('status'))
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl text-blue-800">
                        {{ session('status') }}
                    </div>
                    @endif

                    <!-- Formulaire -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="text-gray-700 font-semibold text-sm uppercase tracking-wide">
                                Adresse email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                                       class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                       placeholder="votre.email@uts.bf" />
                            </div>
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <label for="password" class="text-gray-700 font-semibold text-sm uppercase tracking-wide">
                                Mot de passe
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input id="password" type="password" name="password" required 
                                       class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                       placeholder="••••••••" />
                            </div>
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me et Mot de passe oublié -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="flex items-center group cursor-pointer">
                                <input id="remember_me" type="checkbox" name="remember"
                                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500 transition-all duration-200">
                                <span class="ml-3 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">
                                    Se souvenir de moi
                                </span>
                            </label>

                            @if (Route::has('password.request'))
                            <a class="text-sm text-purple-600 hover:text-purple-800 hover:underline transition-colors duration-200" 
                               href="{{ route('password.request') }}">
                                Mot de passe oublié ?
                            </a>
                            @endif
                        </div>

                        <!-- Bouton de connexion -->
                        <div class="pt-4">
                            <button type="submit" class="w-full justify-center py-3 px-6 border border-transparent rounded-xl shadow-lg text-base font-semibold text-white bg-gradient-to-r from-purple-600 to-purple-800 hover:from-purple-700 hover:to-purple-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transform hover:scale-[1.02] transition-all duration-200 group flex items-center">
                                <i class="fas fa-sign-in-alt mr-2 group-hover:animate-bounce"></i>
                                Se connecter
                            </button>
                        </div>
                    </form>

                    <!-- Footer -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-center text-sm text-gray-500">
                            &copy; {{ date('Y') }} Université Thomas SANKARA. Tous droits réservés.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    <script>
        // Animation pour les messages de session
        document.addEventListener('DOMContentLoaded', function() {
            const statusMessage = document.querySelector('[id^="sessionStatus"]');
            if (statusMessage) {
                setTimeout(() => {
                    statusMessage.style.opacity = '0';
                    statusMessage.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => statusMessage.remove(), 500);
                }, 5000);
            }
        });

        // Validation basique du formulaire
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const email = document.getElementById('email');
                const password = document.getElementById('password');
                
                if (!email.value || !password.value) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs obligatoires.');
                }
            });
        }
    </script>
</body>
</html>