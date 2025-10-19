<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
            
            <!-- Titre -->
            <h2 class="text-2xl font-bold text-gray-800 text-center mb-4">
                {{ __('Mot de passe oublié ?') }}
            </h2>

            <p class="text-sm text-gray-600 text-center mb-6">
                Pas de problème. Entrez votre adresse e-mail et nous vous enverrons 
                un lien pour réinitialiser votre mot de passe.
            </p>

            <!-- Message de session -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Formulaire -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-300" 
                        type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Bouton -->
                <div class="mt-6">
                    <button type="submit" 
                        class="w-full px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md 
                               hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        {{ __('Envoyer le lien de réinitialisation') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
