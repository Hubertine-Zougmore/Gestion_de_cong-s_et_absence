@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Nouvelle demande de congé</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <h2 class="font-bold">Erreurs de validation :</h2>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('demandes.store') }}" method="POST" enctype="multipart/form-data" id="demandeForm">
        @csrf

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <!-- Type de demande -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Type de demande *</label>
                <select name="type" id="type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Sélectionnez un type</option>
                    <option value="conge_annuel" {{ old('type') == 'conge_annuel' ? 'selected' : '' }}>Congé annuel</option>
                    <option value="conge_maternite" {{ old('type') == 'conge_maternite' ? 'selected' : '' }}>Congé de maternité</option>
                    <option value="autorisation_absence" {{ old('type') == 'autorisation_absence' ? 'selected' : '' }}>Autorisation d'absence</option>
                </select>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 mb-2">Date de début *</label>
                    <input type="date" name="date_debut" id="date_debut" value="{{ old('date_debut') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Date de fin *</label>
                    <input type="date" name="date_fin" id="date_fin" value="{{ old('date_fin') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <!-- Nombre de jours (calculé automatiquement) -->
            <div class="mb-4 bg-blue-50 p-4 rounded-lg">
                <label class="block text-gray-700 mb-2 font-semibold">Nombre de jours</label>
                <div class="text-2xl font-bold text-blue-700" id="nombre_jours_display">0 jour(s)</div>
                <input type="hidden" name="nombre_jours" id="nombre_jours" value="0">
                <p class="text-sm text-gray-600 mt-1">Calculé automatiquement en fonction des dates sélectionnées</p>
            </div>

            <!-- Motif -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Motif</label>
                <textarea name="motif" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3">{{ old('motif') }}</textarea>
            </div>

            <!-- Justificatif (uniquement pour maternité et maladie) -->
            <div id="justificatif-field" class="mb-4 hidden">
                <label class="block text-gray-700 mb-2">Justificatif *</label>
                <input type="file" name="justificatif" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Formats acceptés: PDF, JPG, PNG. Taille max: 5MB</p>
                
                <!-- Type de justificatif -->
                <div class="mt-3">
                    <label class="block text-gray-700 mb-2">Type de justificatif *</label>
                    <select name="type_justificatif" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionnez le type</option>
                        <option value="certificat_medical" {{ old('type_justificatif') == 'certificat_medical' ? 'selected' : '' }}>Certificat médical</option>
                        <option value="avis_arret_travail" {{ old('type_justificatif') == 'avis_arret_travail' ? 'selected' : '' }}>Avis d'arrêt de travail</option>
                        <option value="certificat_maternite" {{ old('type_justificatif') == 'certificat_maternite' ? 'selected' : '' }}>Certificat de maternité</option>
                        <option value="autre" {{ old('type_justificatif') == 'autre' ? 'selected' : '' }}>Autre document</option>
                    </select>
                </div>
            </div>

           <!-- VOTRE CODE CORRIGÉ -->

<!-- Message d'information sur les congés annuels -->
<div id="conge-annuel-info" class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
    <div class="flex items-center">
        <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        <span class="text-blue-700 font-medium">
            💡 Rappel : Les congés annuels sont autorisés uniquement en août et septembre.
        </span>
    </div>
</div>

<!-- Afficher les erreurs générales de formulaire -->
@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
        <h2 class="font-bold mb-2">Erreurs de validation</h2>
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Afficher les erreurs de session (success/error) -->
@if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        {{ session('error') }}
    </div>
@endif

<!-- Afficher les quotas disponibles -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-blue-50 p-4 rounded-lg">
        <h3 class="font-semibold text-blue-800">Congés Annuels</h3>
        <p class="text-2xl font-bold">{{ Auth::user()->conge_annuel_restant ?? 0 }}/30 jours</p>
        <p class="text-sm text-blue-600">Août-Septembre seulement</p>
    </div>
    
    <div class="bg-green-50 p-4 rounded-lg">
        <h3 class="font-semibold text-green-800">Absences</h3>
        <p class="text-2xl font-bold">{{ Auth::user()->absence_restante ?? 0 }}/10 jours</p>
    </div>
    
    <div class="bg-purple-50 p-4 rounded-lg">
        <h3 class="font-semibold text-purple-800">Maternité</h3>
        <p class="text-2xl font-bold">{{ Auth::user()->maternite_restante ?? 0 }}/98 jours</p>
    </div>
</div>

<div class="flex justify-end">
    <a href="{{ route('demandes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-3 hover:bg-gray-600">
        Annuler
    </a>
    <button type="submit" onclick="return validateQuota()" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
        Soumettre la demande
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const dateDebutInput = document.getElementById('date_debut');
    const dateFinInput = document.getElementById('date_fin');
    const justificatifField = document.getElementById('justificatif-field');
    const justificatifInput = document.querySelector('input[name="justificatif"]');
    const typeJustificatifSelect = document.querySelector('select[name="type_justificatif"]');
    const nombreJoursDisplay = document.getElementById('nombre_jours_display');
    const nombreJoursInput = document.getElementById('nombre_jours');

function validateQuotas() {
        if (!dateDebutInput.value || !dateFinInput.value) return true;

        const dateDebut = new Date(dateDebutInput.value);
        const dateFin = new Date(dateFinInput.value);
        const jours = Math.ceil((dateFin - dateDebut) / (1000 * 60 * 60 * 24)) + 1;

        // Validation congés annuels
        if (typeSelect.value === 'conge_annuel') {
            const moisDebut = dateDebut.getMonth() + 1;
            const moisFin = dateFin.getMonth() + 1;
            
            if (moisDebut < 8 || moisDebut > 9 || moisFin < 8 || moisFin > 9) {
                alert('❌ Les congés annuels ne sont autorisés qu\'en août et septembre !');
                return false;
            }
            
            if (jours > {{ Auth::user()->conge_annuel_restant }}) {
                alert(`❌ Quota insuffisant ! Vous avez demandé ${jours} jours mais il vous reste seulement {{ Auth::user()->conge_annuel_restant }} jours.`);
                return false;
            }
        }

        // Validation absences
        if (typeSelect.value === 'absence' && jours > {{ Auth::user()->absence_restante }}) {
            alert(`❌ Quota d'absence insuffisant ! Vous avez demandé ${jours} jours mais il vous reste seulement {{ Auth::user()->absence_restante }} jours.`);
            return false;
        }

        return true;
    }

    form.addEventListener('submit', function(e) {
        if (!validateQuotas()) {
            e.preventDefault();
        }
    });
});

 // Fonction pour afficher les erreurs de quota
function showQuotaError(message) {
    // Supprimer toute ancienne erreur
    hideQuotaError();
    
    // Créer la div d'erreur
    const errorDiv = document.createElement('div');
    errorDiv.id = 'quota-error';
    errorDiv.className = 'mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded';
    errorDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <strong>Erreur de validation :</strong> ${message}
        </div>
    `;
    
    // Insérer après les quotas
    const quotasSection = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-3.gap-4.mb-6');
    if (quotasSection) {
        quotasSection.parentNode.insertBefore(errorDiv, quotasSection.nextSibling);
    } else {
        // Fallback : insérer avant le bouton submit
        const submitButton = document.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.closest('.flex.justify-end').parentNode.insertBefore(errorDiv, submitButton.closest('.flex.justify-end'));
        }
    }
    
    // Scroll vers l'erreur
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    
    return false;
}

// Fonction pour cacher les erreurs
function hideQuotaError() {
    const errorDiv = document.getElementById('quota-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Fonction de validation principale CORRIGÉE
function validateQuota() {
    const type = document.getElementById('type').value;
    const dateDebut = document.getElementById('date_debut').value;
    const dateFin = document.getElementById('date_fin').value;
    
    hideQuotaError();
    
    if (!dateDebut || !dateFin) {
        showQuotaError('Veuillez sélectionner les dates de début et de fin.');
        return false;
    }
    
    const debut = new Date(dateDebut);
    const fin = new Date(dateFin);
    
    if (type === 'conge_annuel') {
        const monthDebut = debut.getMonth() + 1;
        const monthFin = fin.getMonth() + 1;
        const anneeDebut = debut.getFullYear();
        const anneeFin = fin.getFullYear();
        
        // Validation mois
        if (monthDebut < 8 || monthDebut > 9 || monthFin < 8 || monthFin > 9) {
            return showQuotaError('Les congés annuels sont strictement réservés aux mois d\'août (8) et septembre (9).');
        }
        
        // Validation année
        if (anneeDebut !== anneeFin) {
            return showQuotaError('Les congés annuels doivent être pris dans la même année civile.');
        }
        
        // Validation durée
        const jours = Math.ceil((fin - debut) / (1000 * 60 * 60 * 24)) + 1;
        if (jours > 30) {
            return showQuotaError(`La durée maximale des congés annuels est de 30 jours. Vous avez sélectionné ${jours} jours.`);
        }
    }
    
    return true;
}

// Gestion de l'affichage de l'info congés annuels
function toggleCongeAnnuelInfo() {
    const type = document.getElementById('type').value;
    const infoDiv = document.getElementById('conge-annuel-info');
    
    if (type === 'conge_annuel' && infoDiv) {
        infoDiv.classList.remove('hidden');
    } else if (infoDiv) {
        infoDiv.classList.add('hidden');
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    const typeField = document.getElementById('type');
    const dateDebutField = document.getElementById('date_debut');
    const dateFinField = document.getElementById('date_fin');
    
    if (typeField) {
        toggleCongeAnnuelInfo();
        typeField.addEventListener('change', toggleCongeAnnuelInfo);
    }
    
    // Validation en temps réel
    if (dateDebutField && dateFinField) {
        [dateDebutField, dateFinField].forEach(field => {
            field.addEventListener('change', function() {
                hideQuotaError();
                validateQuota();
            });
        });
    }
});
// Fonction pour calculer le nombre de jours
function calculerNombreJours() {
    const dateDebutInput = document.getElementById('date_debut');
    const dateFinInput = document.getElementById('date_fin');
    const nombreJoursDisplay = document.getElementById('nombre_jours_display');
    const nombreJoursInput = document.getElementById('nombre_jours');

    if (dateDebutInput.value && dateFinInput.value) {
        const dateDebut = new Date(dateDebutInput.value);
        const dateFin = new Date(dateFinInput.value);

        if (dateFin >= dateDebut) {
            const diffTime = Math.abs(dateFin - dateDebut);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

            nombreJoursDisplay.textContent = diffDays + " jour(s)";
            nombreJoursInput.value = diffDays;
        } else {
            nombreJoursDisplay.textContent = "0 jour(s)";
            nombreJoursInput.value = 0;
        }
    }
}

// Fonction pour gérer l'affichage des champs justificatif
function gererJustificatif() {
    const typeDemande = document.getElementById('type').value;
    const justificatifField = document.getElementById('justificatif_field');
    const typeJustificatifField = document.getElementById('type_justificatif_field');

    if (typeDemande === 'permission' || typeDemande === 'conge_maladie') {
        justificatifField.style.display = 'block';
        typeJustificatifField.style.display = 'block';
    } else {
        justificatifField.style.display = 'none';
        typeJustificatifField.style.display = 'none';
    }
}

// Lancer le calcul et la gestion justificatif au changement
document.getElementById('date_debut').addEventListener('change', calculerNombreJours);
document.getElementById('date_fin').addEventListener('change', calculerNombreJours);
document.getElementById('type').addEventListener('change', gererJustificatif);

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', function () {
    calculerNombreJours();
    gererJustificatif();
});
</script>

@endsection