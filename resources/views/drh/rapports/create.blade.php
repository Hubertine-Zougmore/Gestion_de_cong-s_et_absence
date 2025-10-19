@extends('layouts.app')

@section('content')
<style>
/* ===== STYLES FORMULAIRE CRÉATION RAPPORT ===== */
.rapport-create-container {
    max-width: 1000px;
    margin: 2rem auto;
    padding: 2rem;
}

.rapport-create-card {
    background: white;
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.rapport-create-header {
    text-align: center;
    margin-bottom: 3rem;
}

.rapport-create-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.rapport-create-subtitle {
    font-size: 1.1rem;
    color: #6b7280;
}

/* Formulaire */
.rapport-form-group {
    margin-bottom: 2rem;
}

.rapport-form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.rapport-form-select,
.rapport-form-input,
.rapport-form-textarea {
    width: 100%;
    padding: 1rem 1.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.rapport-form-select:focus,
.rapport-form-input:focus,
.rapport-form-textarea:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    transform: translateY(-2px);
}

.rapport-form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 1rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
}

/* Périodes prédéfinies */
.periodes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.periode-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.periode-card:hover {
    border-color: #4f46e5;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(79, 70, 229, 0.15);
}

.periode-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #4f46e5, #7c3aed);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.periode-card:hover::before {
    transform: scaleX(1);
}

.periode-card.active {
    border-color: #4f46e5;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.periode-card.active::before {
    transform: scaleX(1);
}

.periode-title {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.periode-dates {
    font-size: 0.9rem;
    color: #6b7280;
}

/* Dates personnalisées */
.custom-dates {
    background: #f8fafc;
    padding: 2rem;
    border-radius: 12px;
    margin-top: 1rem;
    border: 2px dashed #d1d5db;
    transition: all 0.3s ease;
}

.custom-dates.active {
    border-color: #4f46e5;
    background: white;
}

.date-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

/* Boutons */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 2px solid #f1f5f9;
}

.btn-create {
    padding: 1rem 2.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
    transform: translateY(-2px);
}

/* Aperçu données */
.data-preview {
    background: linear-gradient(135deg, #f8fafc 0%, #e5e7eb 100%);
    padding: 2.5rem;
    border-radius: 15px;
    margin-top: 3rem;
}

.data-preview-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1.5rem;
    text-align: center;
}

.data-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.data-feature {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.data-feature i {
    font-size: 2rem;
    color: #4f46e5;
    margin-bottom: 1rem;
}

.data-feature h4 {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.data-feature p {
    color: #6b7280;
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
    .rapport-create-container {
        padding: 1rem;
    }
    
    .rapport-create-card {
        padding: 2rem;
    }
    
    .date-grid {
        grid-template-columns: 1fr;
    }
    
    .periodes-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-create {
        width: 100%;
        justify-content: center;
    }
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.6s ease-out;
}

/* Utilitaires */
.text-center { text-align: center; }
.mb-0 { margin-bottom: 0; }
.mt-2 { margin-top: 0.5rem; }
</style>

<div class="rapport-create-container animate-fadeIn">
    <div class="rapport-create-card">
        <!-- En-tête -->
        <div class="rapport-create-header">
            <h1 class="rapport-create-title">📊 Nouveau Rapport</h1>
            <p class="rapport-create-subtitle">Générez un rapport détaillé sur les demandes de congés</p>
        </div>

        <form action="{{ route('rapports.store') }}" method="POST">
            @csrf

            <!-- Type de rapport -->
            <div class="rapport-form-group">
                <label class="rapport-form-label">Type de rapport *</label>
                <select name="type" id="type" class="rapport-form-select" required onchange="toggleCustomDates()">
                    <option value="">Sélectionnez un type de rapport</option>
                    <option value="mensuel" {{ old('type') == 'mensuel' ? 'selected' : '' }}>Rapport Mensuel</option>
                    <option value="trimestriel" {{ old('type') == 'trimestriel' ? 'selected' : '' }}>Rapport Trimestriel</option>
                    <option value="annuel" {{ old('type') == 'annuel' ? 'selected' : '' }}>Rapport Annuel</option>
                    <option value="personnalise" {{ old('type') == 'personnalise' ? 'selected' : '' }}>Période Personnalisée</option>
                </select>
                @error('type')<div class="text-danger mt-2">{{ $message }}</div>@enderror
            </div>

            <!-- Titre -->
            <div class="rapport-form-group">
                <label class="rapport-form-label">Titre du rapport *</label>
                <input type="text" name="titre" class="rapport-form-input" 
                       value="{{ old('titre') }}" placeholder="Ex: Rapport Mensuel - Décembre 2023" required>
                @error('titre')<div class="text-danger mt-2">{{ $message }}</div>@enderror
            </div>

            <!-- Périodes prédéfinies -->
            <div class="rapport-form-group">
                <label class="rapport-form-label">Période prédéfinie</label>
                <div class="periodes-grid">
                    @foreach($periodesPredefinies as $key => $periode)
                    <div class="periode-card" onclick="selectPredefinedPeriod('{{ $periode['debut'] }}', '{{ $periode['fin'] }}')">
                        <div class="periode-title">{{ $periode['label'] }}</div>
                        <div class="periode-dates">
                            du {{ Carbon\Carbon::parse($periode['debut'])->format('d/m/Y') }} 
                            au {{ Carbon\Carbon::parse($periode['fin'])->format('d/m/Y') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Dates personnalisées -->
            <div id="custom-dates" class="custom-dates" style="display: none;">
                <label class="rapport-form-label">Période personnalisée *</label>
                <div class="date-grid">
                    <div>
                        <input type="date" name="date_debut" id="date_debut" 
                               class="rapport-form-input" value="{{ old('date_debut') }}">
                        @error('date_debut')<div class="text-danger mt-2">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <input type="date" name="date_fin" id="date_fin" 
                               class="rapport-form-input" value="{{ old('date_fin') }}">
                        @error('date_fin')<div class="text-danger mt-2">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="rapport-form-group">
                <label class="rapport-form-label">Description</label>
                <textarea name="description" class="rapport-form-textarea" 
                          rows="3" placeholder="Description optionnelle du rapport...">{{ old('description') }}</textarea>
                @error('description')<div class="text-danger mt-2">{{ $message }}</div>@enderror
            </div>

            <!-- Boutons -->
            <div class="form-actions">
                <button type="submit" class="btn-create btn-primary">
                    <i class="fas fa-chart-bar"></i> Générer le Rapport
                </button>
                <a href="{{ route('rapports.index') }}" class="btn-create btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </form>

        <!-- Aperçu des données -->
        <div class="data-preview">
            <h3 class="data-preview-title">📋 Données incluses dans le rapport</h3>
            <div class="data-features">
                <div class="data-feature">
                    <i class="fas fa-chart-pie"></i>
                    <h4>Statistiques Générales</h4>
                    <p>Total demandes, approuvées, rejetées et en attente</p>
                </div>
                <div class="data-feature">
                    <i class="fas fa-tags"></i>
                    <h4>Par Type</h4>
                    <p>Analyse détaillée par type de demande</p>
                </div>
                <div class="data-feature">
                    <i class="fas fa-building"></i>
                    <h4>Par Département</h4>
                    <p>Répartition par direction/département</p>
                </div>
                <div class="data-feature">
                    <i class="fas fa-trending-up"></i>
                    <h4>Évolution</h4>
                    <p>Analyse temporelle mensuelle</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCustomDates() {
    const type = document.getElementById('type').value;
    const customDates = document.getElementById('custom-dates');
    
    if (type === 'personnalise') {
        customDates.style.display = 'block';
        customDates.classList.add('active');
        document.getElementById('date_debut').required = true;
        document.getElementById('date_fin').required = true;
    } else {
        customDates.style.display = 'none';
        customDates.classList.remove('active');
        document.getElementById('date_debut').required = false;
        document.getElementById('date_fin').required = false;
    }
}

function selectPredefinedPeriod(debut, fin) {
    document.getElementById('date_debut').value = debut.split(' ')[0];
    document.getElementById('date_fin').value = fin.split(' ')[0];
    document.getElementById('type').value = 'personnalise';
    toggleCustomDates();
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    toggleCustomDates();
});
</script>
@endsection