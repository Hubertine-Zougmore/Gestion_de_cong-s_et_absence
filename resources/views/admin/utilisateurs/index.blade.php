@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Gestion des utilisateurs</h2>
        <p class="text-gray-600">Gérez les comptes utilisateurs de votre organisation</p>
    </div>
    
    <!-- Messages de session -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Barre d'outils -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <!-- Filtres et recherche -->
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                <!-- Barre de recherche -->
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Rechercher un utilisateur..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                
                <!-- Filtres -->
                <div class="flex space-x-1">
                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'all']) }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request('filter', 'all') === 'all' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                       Tous ({{ $totalUsers ?? 0 }})
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'active']) }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request('filter') === 'active' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                       Actifs ({{ $activeUsers ?? 0 }})
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'inactive']) }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request('filter') === 'inactive' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                       Inactifs ({{ $inactiveUsers ?? 0 }})
                    </a>
                </div>
            </div>

            <!-- Bouton d'ajout -->
            <a href="{{ route('admin.utilisateurs.create') }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center font-medium">
                <i class="fas fa-user-plus mr-2"></i> Ajouter un utilisateur
            </a>
        </div>
    </div>

    <!-- Statistiques rapides -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Total utilisateurs</p>
                <p class="text-2xl font-bold text-gray-900">{{ App\Models\User::count() }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                <i class="fas fa-user-check"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Utilisateurs actifs</p>
                <p class="text-2xl font-bold text-gray-900">{{ App\Models\User::where('is_active', true)->count() }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-red-100 text-red-600 mr-3">
                <i class="fas fa-user-slash"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Utilisateurs inactifs</p>
                <p class="text-2xl font-bold text-gray-900">{{ App\Models\User::where('is_active', false)->count() }}</p>
            </div>
        </div>
    </div>
</div>

    <!-- Tableau -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Utilisateur
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Direction/Poste
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rôle
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date embauche
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="usersTable">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors {{ $user->is_active ? '' : 'bg-red-50' }}" 
                            data-name="{{ strtolower($user->prenom . ' ' . $user->nom) }}" 
                            data-email="{{ strtolower($user->email) }}">
                            
                            <!-- Colonne Utilisateur -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                        {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->prenom }} {{ $user->nom }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $user->matricule ?? 'Sans matricule' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Colonne Contact -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-phone mr-1 text-gray-400"></i>
                                    {{ $user->telephone ?? 'Non renseigné' }}
                                </div>
                            </td>

                            <!-- Colonne Direction/Poste -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 capitalize">
                                    {{ $user->direction ?? '-' }}
                                </div>
                                <div class="text-sm text-gray-500 capitalize">
                                    {{ $user->poste ?? '-' }}
                                </div>
                            </td>

                            <!-- Colonne Rôle -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'drh' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'responsable_hierarchique' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'agent' => 'bg-green-100 text-green-800 border-green-200',
                                        'secretaire_general' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                        'president' => 'bg-red-100 text-red-800 border-red-200'
                                    ];
                                    $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $roleColor }}">
                                    {{ App\Models\User::getRolesList()[$user->role] ?? $user->role }}
                                </span>
                            </td>

                            <!-- Colonne Date embauche -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->date_embauche ? \Carbon\Carbon::parse($user->date_embauche)->translatedFormat('d F Y') : '-' }}
                            </td>

                            <!-- Colonne Statut -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($user->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                        <i class="fas fa-check-circle mr-1.5"></i> Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                        <i class="fas fa-times-circle mr-1.5"></i> Inactif
                                    </span>
                                @endif
                            </td>

                            <!-- Colonne Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <!-- Modification -->
                                    <a href="{{ route('admin.utilisateurs.edit', $user->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors group flex items-center"
                                       title="Modifier l'utilisateur">
                                        <i class="fas fa-edit mr-1.5 group-hover:scale-110 transition-transform"></i>
                                        <span class="hidden lg:inline">Modifier</span>
                                    </a>

                                    <!-- Activation/Désactivation -->
                                    @if ($user->is_active)
                                        <form action="{{ route('admin.utilisateurs.desactiver', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-orange-600 hover:text-orange-900 transition-colors group flex items-center"
                                                    onclick="return confirm('Désactiver l\\'utilisateur {{ addslashes($user->prenom) }} {{ addslashes($user->nom) }} ?')"
                                                    title="Désactiver l'utilisateur">
                                                <i class="fas fa-user-slash mr-1.5 group-hover:scale-110 transition-transform"></i>
                                                <span class="hidden lg:inline">Désactiver</span>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.utilisateurs.reactiver', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-green-600 hover:text-green-900 transition-colors group flex items-center"
                                                    title="Réactiver l'utilisateur">
                                                <i class="fas fa-user-check mr-1.5 group-hover:scale-110 transition-transform"></i>
                                                <span class="hidden lg:inline">Réactiver</span>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Suppression -->
                                    @if($user->id !== Auth::id())
                                        <form action="{{ route('admin.utilisateurs.destroy', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 transition-colors group flex items-center"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement l\\'utilisateur {{ addslashes($user->prenom) }} {{ addslashes($user->nom) }} ?')"
                                                    title="Supprimer définitivement">
                                                <i class="fas fa-trash mr-1.5 group-hover:scale-110 transition-transform"></i>
                                                <span class="hidden lg:inline">Supprimer</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-users text-5xl mb-4"></i>
                                    <p class="text-lg font-medium text-gray-500 mb-2">Aucun utilisateur trouvé</p>
                                    <p class="text-sm text-gray-400">Commencez par ajouter votre premier utilisateur</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="mt-6 bg-white px-4 py-3 rounded-lg shadow-sm border border-gray-200">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Script pour la recherche en temps réel -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const usersTable = document.getElementById('usersTable');
    
    if (searchInput && usersTable) {
        const rows = usersTable.getElementsByTagName('tr');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            for (let row of rows) {
                if (row.dataset.name && row.dataset.email) {
                    const nameMatch = row.dataset.name.includes(searchTerm);
                    const emailMatch = row.dataset.email.includes(searchTerm);
                    
                    if (nameMatch || emailMatch || searchTerm === '') {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            }
        });
    }
});
</script>

<!-- Ajout des icônes Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
@endsection