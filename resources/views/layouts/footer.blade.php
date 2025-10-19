{{-- resources/views/layouts/footer.blade.php --}}
<footer class="bg-indigo-transparent border-t border-white/20 mt-auto">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Section principale --}}
            <div class="lg:col-span-2">
                {{-- Logo + titre --}}
                <div class="flex items-center space-x-3 mb-4">
                    <img src="{{ asset('images/logo-uts.png') }}" 
                         alt="Université Thomas SANKARA" 
                         class="w-20 h-20 object-contain rounded-full bg-purple-200/50 p-2 shadow-md">
                    <div>
                        <h3 class="text-xl font-bold text-white">
                            Gestion des Congés et autorisations d'absence
                        </h3>
                        <p class="text-sm text-purple-100">Université Thomas SANKARA</p>
                    </div>
                </div>

                {{-- Description --}}
                <p class="text-purple-100 mb-6 max-w-md">
                    Plateforme moderne et intuitive pour la gestion des congés et autorisations d'absence. 
                    Simplifiez vos démarches administratives avec notre solution digitale.
                </p>

                {{-- Réseaux sociaux --}}
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center text-white hover:scale-110 transition-all duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center text-white hover:scale-110 transition-all duration-300">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center text-white hover:scale-110 transition-all duration-300">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center text-white hover:scale-110 transition-all duration-300">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>

            {{-- Liens rapides --}}
            <div>
                <h4 class="font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-link text-purple-200 mr-2"></i>
                    Liens Rapides
                </h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('home') }}" class="text-purple-100 hover:text-white transition-colors duration-200 flex items-center group">
                            <i class="fas fa-chevron-right text-xs text-purple-200 mr-2 group-hover:text-white group-hover:translate-x-1 transition-all duration-200"></i>
                            Accueil
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-purple-100 hover:text-white transition-colors duration-200 flex items-center group">
                            <i class="fas fa-chevron-right text-xs text-purple-200 mr-2 group-hover:text-white group-hover:translate-x-1 transition-all duration-200"></i>
                            Guide d'utilisation
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-purple-100 hover:text-white transition-colors duration-200 flex items-center group">
                            <i class="fas fa-chevron-right text-xs text-purple-200 mr-2 group-hover:text-white group-hover:translate-x-1 transition-all duration-200"></i>
                            FAQ
                        </a>
                    </li>
                </ul>
            </div>
            
            {{-- Contact --}}
            <div>
                <h4 class="font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-phone text-purple-200 mr-2"></i>
                    Contact
                </h4>
                <ul class="space-y-3">
                    <li class="flex items-start space-x-3">
                        <i class="fas fa-map-marker-alt text-purple-200 mt-1"></i>
                        <div>
                            <p class="text-purple-100 text-sm">Université de Ouagadougou</p>
                            <p class="text-purple-200 text-xs">03 BP 7021 Ouagadougou 03</p>
                        </div>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-phone text-purple-200"></i>
                        <a href="tel:+22625307018" class="text-purple-100 hover:text-white transition-colors duration-200 text-sm">
                            +226 25 30 70 18
                        </a>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-purple-200"></i>
                        <a href="mailto:rh@univ-ouaga.bf" class="text-purple-100 hover:text-white transition-colors duration-200 text-sm">
                            rh@univ-ouaga.bf
                        </a>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-clock text-purple-200"></i>
                        <div class="text-purple-100 text-sm">
                            <p>Lun - Ven: 7h30 - 15h30</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        
        {{-- Copyright et liens légaux --}}
        <div class="mt-8 pt-8 border-t border-purple-200/30">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-2 text-purple-100 text-sm mb-4 md:mb-0">
                    <i class="fas fa-copyright"></i>
                    <span>{{ date('Y') }} Université de Ouagadougou. Tous droits réservés.</span>
                </div>
                <div class="flex items-center space-x-6 text-sm">
                    <a href="#" class="text-purple-100 hover:text-white transition-colors duration-200">
                        Politique de confidentialité
                    </a>
                    <a href="#" class="text-purple-100 hover:text-white transition-colors duration-200">
                        Conditions d'utilisation
                    </a>
                    <a href="#" class="text-purple-100 hover:text-white transition-colors duration-200">
                        Support technique
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Bouton retour en haut --}}
    <button id="scrollToTop" class="fixed bottom-6 right-6 w-12 h-12 bg-gradient-to-br from-purple-600 to-purple-800 text-white rounded-full shadow-xl hover:shadow-2xl opacity-0 invisible transition-all duration-300 z-40">
        <i class="fas fa-chevron-up"></i>
    </button>
</footer>

<style>
    .bg-indigo-transparent {
        background-color: rgba(79, 70, 229, 0.88); /* Indigo avec 88% d'opacité */
        backdrop-filter: blur(12px);
    }
</style>

<script>
    // Bouton retour en haut
    window.addEventListener('scroll', function() {
        const scrollButton = document.getElementById('scrollToTop');
        if (window.pageYOffset > 300) {
            scrollButton.classList.remove('opacity-0', 'invisible');
            scrollButton.classList.add('opacity-100', 'visible');
        } else {
            scrollButton.classList.add('opacity-0', 'invisible');
            scrollButton.classList.remove('opacity-100', 'visible');
        }
    });
    
    document.getElementById('scrollToTop').addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>