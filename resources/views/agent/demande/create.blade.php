@extends('layouts.app')

@section('title', 'Nouvelle Demande de Congé')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Résumé des quotas -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Vos Quotas de Congés {{ date('Y') }}</h5>
                </div>
                <div class="card-body">
                    @php
                        $congeService = new \App\Services\CongeService();
                        $resumeConges = $congeService->getResumeCongés(auth()->user());
                    @endphp
                    
                    <div class="row">
                        @foreach($resumeConges as $type => $info)
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title">{{ $info['nom'] }}</h6>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Utilisés:</span>
                                            <span class="fw-bold">{{ $info['jours_utilises'] }} jours</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Restants:</span>
                                            <span class="fw-bold text-success">{{ $info['jours_restants'] }} jours</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar" 
                                                 style="width: {{ $info['pourcentage_utilise'] }}%"
                                                 aria-valuenow="{{ $info['pourcentage_utilise'] }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $info['pourcentage_utilise'] }}% utilisé</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Formulaire de demande -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Nouvelle Demande de Congé</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('agent.mes-demandes.store') }}" id="demandeForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="type_conge" class="form-label">Type de Congé *</label>
                                <select class="form-select @error('type_conge') is-invalid @enderror" 
                                        id="type_conge" 
                                        name="type_conge" 
                                        required>
                                    <option value="">Choisissez un type de congé</option>
                                    @foreach(config('conges.types') as $key => $config)
                                        <option value="{{ $key }}" 
                                                {{ old('type_conge') == $key ? 'selected' : '' }}
                                                data-quota="{{ $config['quota_jours'] }}"
                                                data-restant="{{ $resumeConges[$key]['jours_restants'] }}">
                                            {{ $config['nom'] }} 
                                            ({{ $resumeConges[$key]['jours_restants'] }}/{{ $config['quota_jours'] ?? 'Illimité' }} jours restants)
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_conge')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Sélectionnez le type de congé souhaité
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_debut" class="form-label">Date de Début *</label>
                                <input type="date" 
                                       class="form-control @error('date_debut') is-invalid @enderror" 
                                       id="date_debut" 
                                       name="date_debut" 
                                       value="{{ old('date_debut') }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_fin" class="form-label">Date de Fin *</label>
                                <input type="date" 
                                       class="form-control @error('date_fin') is-invalid @enderror" 
                                       id="date_fin" 
                                       name="date_fin" 
                                       value="{{ old('date_fin') }}"
                                       required>
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Affichage du nombre de jours -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="alert alert-info d-none" id="joursCalcules">
                                    <strong>Nombre de jours ouvrables demandés :</strong> 
                                    <span id="nombreJours">0</span> jours
                                </div>
                                <div class="alert alert-warning d-none" id="quotaInsuffisant">
                                    <strong>Attention :</strong> Vous n'avez pas suffisamment de jours disponibles pour ce type de congé.
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="motif" class="form-label">Motif (optionnel)</label>
                            <textarea class="form-control @error('motif') is-invalid @enderror" 
                                      id="motif" 
                                      name="motif" 
                                      rows="4" 
                                      placeholder="Précisez le motif de votre demande...">{{ old('motif') }}</textarea>
                            @error('motif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('agent.mes-demandes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-paper-plane me-1"></i> Soumettre la Demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');
    const typeConge = document.getElementById('type_conge');
    const joursCalculesDiv = document.getElementById('joursCalcules');
    const nombreJoursSpan = document.getElementById('nombreJours');
    const quotaInsuffisantDiv = document.getElementById('quotaInsuffisant');
    const submitBtn = document.getElementById('submitBtn');

    function calculerJoursOuvrables(debut, fin) {
        if (!debut || !fin) return 0;
        
        const dateDebut = new Date(debut);
        const dateFin = new Date(fin);
        let jours = 0;
        
        const current = new Date(dateDebut);
        while (current <= dateFin) {
            const dayOfWeek = current.getDay();
            // Exclure samedi (6) et dimanche (0)
            if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                jours++;
            }
            current.setDate(current.getDate() + 1);
        }
        
        return jours;
    }

    function verifierDisponibilite() {
        const debut = dateDebut.value;
        const fin = dateFin.value;
        const type = typeConge.value;

        if (!debut || !fin || !type) {
            joursCalculesDiv.classList.add('d-none');
            quotaInsuffisantDiv.classList.add('d-none');
            return;
        }

        const nombreJours = calculerJoursOuvrables(debut, fin);
        nombreJoursSpan.textContent = nombreJours;
        
        if (nombreJours > 0) {
            joursCalculesDiv.classList.remove('d-none');
            
            // Vérifier le quota disponible
            const selectedOption = typeConge.options[typeConge.selectedIndex];
            const joursRestants = parseInt(selectedOption.dataset.restant);
            
            if (nombreJours > joursRestants) {
                quotaInsuffisantDiv.classList.remove('d-none');
                quotaInsuffisantDiv.innerHTML = `
                    <strong>Attention :</strong> Vous demandez ${nombreJours} jours mais vous n'avez que ${joursRestants} jours disponibles pour ce type de congé.
                `;
                submitBtn.disabled = true;
                submitBtn.classList.add('btn-secondary');
                submitBtn.classList.remove('btn-primary');
            } else {
                quotaInsuffisantDiv.classList.add('d-none');
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-secondary');
                submitBtn.classList.add('btn-primary');
            }
        } else {
            joursCalculesDiv.classList.add('d-none');
            quotaInsuffisantDiv.classList.add('d-none');
        }
    }

    // Mettre à jour la date minimum de fin quand la date de début change
    dateDebut.addEventListener('change', function() {
        if (this.value) {
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            dateFin.min = nextDay.toISOString().split('T')[0];
        }
        verifierDisponibilite();
    });

    dateFin.addEventListener('change', verifierDisponibilite);
    typeConge.addEventListener('change', verifierDisponibilite);
});
</script>
@endsection