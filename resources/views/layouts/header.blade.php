<header class="glass-effect border-b border-white/20 sticky top-0 z-50 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo et titre --}}
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    <img src="{{ asset('images/logo-uts.png') }}" 
                         alt="Université Thomas SANKARA" 
                         class="w-20 h-20 object-contain rounded-full bg-purple-200/50 p-2 shadow-md">
                    <div class="hidden sm:block">
                        <h1 class="text-xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                            Gestion des Congés et d'Absence
                        </h1>
                        <p class="text-xs text-gray-500 -mt-1">Université Thomas SANKARA</p>
                    </div>
                </a>
            </div>

            {{-- Navigation principale --}}
            <div class="hidden md:flex items-center space-x-8">
                @auth
                    

                    {{-- Mes Demandes pour tous --}}
                    <a href="{{ route('demandes.index') }}" class="flex items-center space-x-2 text-gray-700 hover:text-green-600 transition-colors duration-200 group">
                        <i class="fas fa-file-alt group-hover:scale-110 transition-transform duration-200"></i>
                        <span class="font-medium">Mes Demandes</span>
                    </a>

  {{-- Notifications --}}
<div class="relative" x-data="{ open: false }">
    {{-- Bouton cloche --}}
    <button @click="open = !open"
            class="relative p-2 text-gray-700 hover:text-blue-600 transition-colors duration-200">
        <i class="fas fa-bell text-lg"></i>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span id="notif-badge"
                  class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open"
         x-cloak
         x-transition
         @click.outside="open = false"
         class="absolute right-0 top-full mt-2 w-72 bg-white rounded-xl shadow-2xl border border-gray-100 z-50">
        
        <div class="p-3 border-b border-gray-100 font-semibold text-gray-700">
            Notifications
        </div>

        {{-- Liste défilante --}}
        <div class="max-h-60 overflow-y-auto">
            @forelse(auth()->user()->notifications as $notif)
                <div id="notif-{{ $notif->id }}"
                     class="flex items-center justify-between px-4 py-2 text-sm 
                            {{ $notif->read_at ? 'text-gray-400' : 'text-gray-700 font-medium bg-blue-50' }}">
                    
                    <span>{{ $notif->data['message'] ?? 'Nouvelle notification' }}</span>
                    
                    @if(!$notif->read_at)
                        <button type="button"
                                class="text-blue-600 text-xs hover:underline"
                                onclick="markAsRead('{{ $notif->id }}')">
                            Marquer lu
                        </button>
                    @endif
                </div>
            @empty
                <div class="px-4 py-2 text-sm text-gray-400">
                    Aucune notification
                </div>
            @endforelse
        </div>

        <div class="border-t">
            <a href="{{ route('notifications.index') }}" 
               class="block text-center text-blue-600 py-2 text-sm hover:bg-gray-50"
               @click="open = false">
                Voir toutes
            </a>
        </div>
    </div>
</div>



 {{-- Lien Dashboard spécifique --}}
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 text-gray-700 hover:text-purple-600 transition-colors duration-200 group">
                            <i class="fas fa-tachometer-alt group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="font-medium">Dashboard Admin</span>
                        </a>
                    @elseif(auth()->user()->role === 'drh')
                        <a href="{{ route('drh.dashboard') }}" class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600 transition-colors duration-200 group">
                            <i class="fas fa-clipboard-check group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="font-medium">Dashboard DRH</span>
                        </a>
                    @elseif(auth()->user()->role === 'responsable_hierarchique')
                        <a href="{{ route('responsable.dashboard') }}" class="flex items-center space-x-2 text-gray-700 hover:text-purple-600 transition-colors duration-200 group">
                            <i class="fas fa-tachometer-alt group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="font-medium">Dashboard Responsable</span>
                        </a>
                       @elseif(auth()->user()->role === 'secretaire_general')
       <a href="{{ url('/secretaire_general/dashboard') }}" class="flex items-center space-x-2 text-gray-700 hover:text-purple-600 transition-colors duration-200 group">
        <i class="fas fa-tachometer-alt group-hover:scale-110 transition-transform duration-200"></i>
        <span class="font-medium">Dashboard SG</span>
       </a>
        @elseif(auth()->user()->role === 'president')
       <a href="{{ url('/president/dashboard') }}" class="flex items-center space-x-2 text-gray-700 hover:text-purple-600 transition-colors duration-200 group">
        <i class="fas fa-tachometer-alt group-hover:scale-110 transition-transform duration-200"></i>
        <span class="font-medium">Dashboard president</span>
        </a>
        @elseif(auth()->user()->role === 'agent')
       <a href="{{ url('/agent/dashboard') }}" class="flex items-center space-x-2 text-gray-700 hover:text-purple-600 transition-colors duration-200 group">
        <i class="fas fa-tachometer-alt group-hover:scale-110 transition-transform duration-200"></i>
        <span class="font-medium">Dashboard agent</span>
        </a>
                    @endif
                @endauth
            </div>

            {{-- Actions utilisateur --}}
            <div class="flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="hidden sm:flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-sign-in-alt"></i>
                        <span class="font-medium">Connexion</span>
                    </a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:shadow-blue-500/25 transition-all duration-300 font-medium">
                        <i class="fas fa-user-plus mr-2"></i>
                        Inscription
                    </a>
                @endguest

                @auth
                   {{-- Menu utilisateur --}}
<div class="relative group">
    <button id="userMenuBtn" class="flex items-center space-x-2 p-2 text-gray-700 hover:text-blue-600">
        <i class="fas fa-user-circle text-lg"></i>
        <span>{{ auth()->user()->prenom }}</span>
    </button>

    {{-- Dropdown affiché au survol --}}
    <div id="userMenuDropdown"
         class="hidden group-hover:block absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-2xl border border-gray-100 z-50">
         
        <div class="p-3 border-b border-gray-100">
            <p class="font-semibold text-gray-800">
                {{ auth()->user()->prenom }} {{ auth()->user()->nom }}
            </p>
            <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full mt-1 capitalize">
                {{ auth()->user()->role }}
            </span>
        </div>

        <div class="py-2">
            <a href="{{ route('profile.edit') }}"
               class="flex items-center space-x-3 px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                <i class="fas fa-user-cog text-gray-400"></i>
                <span>Mon Profil</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center space-x-3 px-4 py-2 text-red-600 hover:bg-red-50 w-full text-left">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </div>
</div>
                @endauth  

    {{-- Menu mobile --}}
    <div id="mobileMenu" class="md:hidden hidden bg-white/95 backdrop-blur-sm border-t border-gray-200">
        <div class="px-4 py-3 space-y-3">
            @auth
               <!-- <a href="{{ route('home') }}" class="flex items-center space-x-3 text-gray-700 hover:text-blue-600 transition-colors duration-200">
                    <i class="fas fa-home"></i>
                    <span>Accueil</span>
                </a>-->
                <a href="{{ route('demandes.index') }}" class="flex items-center space-x-3 text-gray-700 hover:text-green-600 transition-colors duration-200">
                    <i class="fas fa-file-alt"></i>
                    <span>Mes Demandes</span>
                </a>
                <a href="#" class="flex items-center space-x-3 text-gray-700 hover:text-blue-600 transition-colors duration-200">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 text-gray-700 hover:text-purple-600 transition-colors duration-200">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard Admin</span>
                    </a>
                
                @elseif(auth()->user()->role === 'responsable_hierarchique')
                    <a href="{{ route('responsable.dashboard') }}" class="flex items-center space-x-3 text-gray-700 hover:text-purple-600 transition-colors duration-200">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard Responsable</span>
                    </a>
                @endif
            @endauth
            @guest
                <a href="{{ route('login') }}" class="flex items-center space-x-3 text-gray-700 hover:text-blue-600 transition-colors duration-200">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Connexion</span>
                </a>
            @endguest
        </div>
    </div>
</header>

<script>
    // Toggle mobile menu
    function toggleMobileMenu() {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    }

    // Toggle user dropdown
    document.getElementById('userMenuBtn')?.addEventListener('click', function() {
        document.getElementById('userMenuDropdown').classList.toggle('hidden');
    });
    
function markAsRead(id) {
    fetch(`/notifications/${id}/read`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            "Accept": "application/json"
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "ok") {
            // Masquer ou griser la notif
            document.getElementById(`notif-${id}`).classList.add("text-gray-400");
            // Décrémenter le badge
            let badge = document.getElementById("notif-badge");
            if (badge) {
                let count = parseInt(badge.innerText) - 1;
                badge.innerText = count > 0 ? count : "";
                if (count <= 0) badge.remove();
            }
        }
    });
}
</script>
