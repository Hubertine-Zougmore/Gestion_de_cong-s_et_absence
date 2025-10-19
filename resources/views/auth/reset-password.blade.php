<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation - Université Thomas SANKARA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass-effect {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        .floating-animation-delay {
            animation: float 6s ease-in-out infinite;
            animation-delay: 2s;
        }
        .floating-animation-delay-2 {
            animation: float 6s ease-in-out infinite;
            animation-delay: 4s;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-screen flex">
        <!-- Section violette à gauche -->
        <div class="hidden lg:flex lg:w-1/3 bg-gradient-to-br from-purple-700 via-purple-800 to-indigo-900 relative overflow-hidden">
            <!-- Effets décoratifs animés -->
            <div class="absolute top-0 left-0 w-32 h-32 bg-white/10 rounded-full -translate-x-16 -translate-y-16 floating-animation"></div>
            <div class="absolute bottom-0 right-0 w-48 h-48 bg-white/10 rounded-full translate-x-24 translate-y-24 floating-animation-delay"></div>
            <div class="absolute top-1/2 left-0 w-24 h-24 bg-white/5 rounded-full -translate-x-12 floating-animation-delay-2"></div>
            <div class="absolute top-1/4 right-0 w-20 h-20 bg-white/8 rounded-full translate-x-10 floating-animation"></div>
            
            <!-- Contenu centré -->
            <div class="flex flex-col justify-center items-center p-12 text-white relative z-10 w-full text-center">
                <!-- Logo avec effet de sécurité -->
                <div class="w-28 h-28 mx-auto mb-8 bg-white/20 rounded-full flex items-center justify-center shadow-2xl glass-effect border border-white/30 relative">
                    <div class="w-20 h-20 bg-white/30 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <!-- Indicateur de sécurité -->
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center border-2 border-white">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Titre avec icône de sécurité -->
                <div class="flex items-center mb-6">
                    <svg class="w-6 h-6 text-purple-200 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <h1 class="text-3xl font-bold">Sécurité</h1>
                </div>
                
                <h2 class="text-2xl font-semibold mb-4 text-purple-100">Réinitialisation</h2>
                
                <!-- Description */
                <div class="w-16 h-1 bg-white/50 rounded-full mb-6"></div>
                <p class="text-purple-100 text-center mb-4 leading-relaxed">
                    Créez un nouveau mot de passe sécurisé pour votre compte UTS
                </p>
                
                <!-- Conseils de sécurité -->
                <div class="mt-8 bg-white/10 rounded-2xl p-4 glass-effect border border-white/20">
                    <h3 class="text-sm font-semibold mb-2 text-purple-100">Conseils de sécurité :</h3>
                    <ul class="text-xs text-purple-200 space-y-1 text-left">
                        <li class="flex items-center">
                            <div class="w-1 h-1 bg-purple-300 rounded-full mr-2"></div>
                            Au moins 8 caractères
                        </li>
                        <li class="flex items-center">
                            <div class="w-1 h-1 bg-purple-300 rounded-full mr-2"></div>
                            Majuscules et minuscules
                        </li>
                        <li class="flex items-center">
                            <div class="w-1 h-1 bg-purple-300 rounded-full mr-2"></div>
                            Chiffres et symboles
                        </li>
                    </ul>
                </div>
                
                <!-- Éléments décoratifs -->
                <div class="mt-8 flex space-x-2">
                    <div class="w-2 h-2 bg-white/40 rounded-full animate-pulse"></div>
                    <div class="w-2 h-2 bg-white/30 rounded-full animate-pulse delay-100"></div>
                    <div class="w-2 h-2 bg-white/20 rounded-full animate-pulse delay-200"></div>
                </div>
            </div>
        </div>

        <!-- Section principale avec formulaire -->
        <div class="flex-1 flex items-center justify-center relative overflow-hidden">
            <!-- Fond dégradé -->
            <div class="absolute inset-0 z-0 bg-gradient-to-br from-blue-50 via-purple-50 to-indigo-100"></div>
            
            <!-- Éléments décoratifs flottants -->
            <div class="absolute top-10 right-10 w-20 h-20 bg-purple-200/30 rounded-full blur-xl floating-animation"></div>
            <div class="absolute bottom-20 left-10 w-16 h-16 bg-blue-200/30 rounded-full blur-xl floating-animation-delay"></div>

            <!-- Formulaire -->
            <div class="relative z-10 w-full max-w-lg mx-6">
                <!-- Header mobile -->
                <div class="text-center mb-8 lg:hidden">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-purple-600 to-indigo-700 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Réinitialisation</h2>
                    <p class="text-gray-600">Université Thomas SANKARA</p>
                </div>

                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/50 glass-effect">
                    <!-- En-tête du formulaire -->
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-purple-100 to-purple-200 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Nouveau mot de passe</h3>
                        <p class="text-gray-600">Créez un mot de passe sécurisé pour votre compte</p>
                    </div>

                    <!-- Formulaire -->
                    <form class="space-y-6">
                        <!-- Token caché -->
                        <input type="hidden" name="token" value="sample-token">

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="text-gray-700 font-semibold text-sm uppercase tracking-wide flex items-center">
                                <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Adresse email
                            </label>
                            <div class="relative">
                                <input id="email" type="email" name="email" required 
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                                       placeholder="votre.email@uts.bf" 
                                       value="utilisateur@uts.bf" readonly />
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Nouveau mot de passe -->
                        <div class="space-y-2">
                            <label for="password" class="text-gray-700 font-semibold text-sm uppercase tracking-wide flex items-center">
                                <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Nouveau mot de passe
                            </label>
                            <div class="relative">
                                <input id="password" type="password" name="password" required 
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                       placeholder="Entrez votre nouveau mot de passe" />
                                <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="togglePassword('password')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            <!-- Indicateur de force du mot de passe -->
                            <div class="mt-2">
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 w-0 rounded-full transition-all duration-300" id="password-strength"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Force du mot de passe</p>
                            </div>
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="text-gray-700 font-semibold text-sm uppercase tracking-wide flex items-center">
                                <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Confirmez le mot de passe
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" type="password" name="password_confirmation" required 
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                       placeholder="Confirmez votre mot de passe" />
                                <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="togglePassword('password_confirmation')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Bouton de réinitialisation -->
                        <div class="pt-6">
                            <button type="submit" class="w-full justify-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-base font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transform hover:scale-[1.02] transition-all duration-200 group flex items-center">
                                <svg class="w-5 h-5 mr-2 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                Réinitialiser le mot de passe
                            </button>
                        </div>

                        <!-- Lien retour -->
                        <div class="text-center pt-4">
                            <a href="#" class="text-sm text-purple-600 hover:text-purple-800 hover:underline transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Retour à la connexion
                            </a>
                        </div>
                    </form>

                    <!-- Footer -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-center text-sm text-gray-500">
                            &copy; 2024 Université Thomas SANKARA. Tous droits réservés.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
        }

        // Simulateur de force de mot de passe
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            const strength = Math.min(password.length * 10, 100);
            const strengthBar = document.getElementById('password-strength');
            strengthBar.style.width = strength + '%';
        });
    </script>
</body>
</html>