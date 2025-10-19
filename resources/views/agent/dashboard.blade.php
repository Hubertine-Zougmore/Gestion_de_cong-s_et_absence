@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Bienvenue, {{ Auth::user()->prenom }} {{ Auth::user()->nom }}</h1>
    <p class="text-gray-600 mb-6">Vous êtes connecté en tant qu'<strong>Agent</strong>.</p>

   

    <!-- Bouton nouvelle demande -->
    <a href="{{ route('demandes.create') }}" class="mb-6 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        ➕ Nouvelle demande
    </a>

    <h2 class="text-xl font-semibold mb-4">Mes demandes</h2>
    
    <!-- Vérification des demandes -->
    @if(isset($demandes) && $demandes->count() > 0)
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Type</th>
                        <th class="p-3 text-left">Début</th>
                        <th class="p-3 text-left">Fin</th>
                        <th class="p-3 text-left">Statut</th>
                        <th class="p-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($demandes as $demande)
                        <tr class="border-b border-gray-200">
                            <td class="p-3 capitalize">{{ $demande->type }}</td>
                            <td class="p-3">{{ $demande->date_debut->format('d/m/Y') }}</td>
                            <td class="p-3">{{ $demande->date_fin->format('d/m/Y') }}</td>
                            <td class="p-3">
                                @if ($demande->statut === 'en_attente' || $demande->statut === 'en attente')
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-semibold">En attente</span>
                                @elseif ($demande->statut === 'approuve')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Approuvée</span>
                                @elseif ($demande->statut === 'rejete')
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Rejetée</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-semibold">{{ $demande->statut }}</span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($demande->statut === 'en_attente' || $demande->statut === 'en attente')
                                    <div class="flex space-x-2">
                                        <a href="{{ route('demandes.edit', $demande->id) }}" class="text-blue-500 hover:text-blue-700">
                                            Modifier
                                        </a>
                                        <form action="{{ route('demandes.destroy', $demande->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Supprimer cette demande ?')">
                                                 Supprimer
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Aucune action possible</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @else
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-gray-500">Cliquez ici pour voir vos demandes.</p>
            <a href="{{ route('demandes.index') }}" class="mt-3 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Mes demandes
            </a>
        </div>
    @endif
</div>
<script>
// Fonction de validation des quotas
function validateQuota() {
    const type = document.getElementById('type').value;
    const dateDebut = document.getElementById('date_debut').value;
    const dateFin = document.getElementById('date_fin').value;
    
    if (!dateDebut || !dateFin) return true;
    
    const debut = new Date(dateDebut);
    const fin = new Date(dateFin);
    const jours = Math.ceil((fin - debut) / (1000 * 60 * 60 * 24)) + 1;
    
    if (type === 'conge_annuel') {
        const month = debut.getMonth() + 1;
        if (month !== 8 && month !== 9) {
            alert('Les congés annuels ne sont autorisés qu\'en août et septembre');
            return false;
        }
    }
    
    return true;
}

// Gestion des onglets
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    // Afficher le premier onglet par défaut
    if (tabContents.length > 0) {
        tabContents[0].classList.remove('hidden');
    }

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');

            // Réinitialiser tous les onglets
            tabButtons.forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Activer l'onglet sélectionné
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('border-blue-500', 'text-blue-600');
            
            const targetElement = document.getElementById(targetTab);
            if (targetElement) {
                targetElement.classList.remove('hidden');
            }
        });
    });
});
</script>
@endsection