@extends('layouts.app')

@section('content')
<style>
/* ===== STYLES INDEX RAPPORTS ===== */
.rapports-index-container {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 2rem;
}

.rapports-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.rapports-title {
    font-size: 2.8rem;
    font-weight: 800;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0;
}

.btn-new-rapport {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    padding: 1rem 2rem;
    border-radius: 15px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(79, 70, 229, 0.3);
}

.btn-new-rapport:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(79, 70, 229, 0.4);
}

/* Grille des rapports */
.rapports-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.rapport-card {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.rapport-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #4f46e5, #7c3aed);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.rapport-card:hover::before {
    transform: scaleX(1);
}

.rapport-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.15);
}

.rapport-card-header {
    margin-bottom: 1.5rem;
}

.rapport-card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.rapport-card-type {
    display: inline-block;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.type-mensuel { background: #dbeafe; color: #1e40af; }
.type-trimestriel { background: #fce7f3; color: #be185d; }
.type-annuel { background: #dcfce7; color: #166534; }
.type-personnalise { background: #fef3c7; color: #92400e; }

.rapport-card-dates {
    color: #6b7280;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 10px;
}

.rapport-card-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-mini {
    text-align: center;
    padding: 0.8rem;
    background: #f8fafc;
    border-radius: 10px;
}

.stat-mini-number {
    font-size: 1.2rem;
    font-weight: 700;
    color: #4f46e5;
    margin-bottom: 0.2rem;
}

.stat-mini-label {
    font-size: 0.7rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.rapport-card-actions {
    display: flex;
    gap: 0.8rem;
    flex-wrap: wrap;
}

.btn-action {
    padding: 0.8rem 1.2rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    border: 2px solid transparent;
}

.btn-view {
    background: #4f46e5;
    color: white;
}

.btn-view:hover {
    background: #3730a3;
    transform: translateY(-2px);
}

.btn-pdf {
    background: #10b981;
    color: white;
}

.btn-pdf:hover {
    background: #059669;
    transform: translateY(-2px);
}

.btn-delete {
    background: #ef4444;
    color: white;
    border: none;
    cursor: pointer;
}

.btn-delete:hover {
    background: #dc2626;
    transform: translateY(-2px);
}

/* État vide */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.empty-state-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    opacity: 0.5;
}

.empty-state-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: 1rem;
}

.empty-state-text {
    color: #6b7280;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 3rem;
}

.pagination {
    display: flex;
    gap: 0.5rem;
    list-style: none;
    padding: 0;
}

.page-item {
    display: inline-block;
}

.page-link {
    padding: 0.8rem 1.2rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    text-decoration: none;
    color: #4f46e5;
    font-weight: 600;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: #4f46e5;
    color: white;
    border-color: #4f46e5;
}

.page-item.active .page-link {
    background: #4f46e5;
    color: white;
    border-color: #4f46e5;
}

/* Responsive */
@media (max-width: 768px) {
    .rapports-index-container {
        padding: 1rem;
    }
    
    .rapports-header {
        flex-direction: column;
        text-align: center;
    }
    
    .rapports-title {
        font-size: 2.2rem;
    }
    
    .rapports-grid {
        grid-template-columns: 1fr;
    }
    
    .rapport-card {
        padding: 2rem;
    }
    
    .rapport-card-actions {
        flex-direction: column;
    }
    
    .btn-action {
        width: 100%;
        justify-content: center;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.rapport-card {
    animation: fadeInUp 0.6s ease-out;
}

/* Badge de statut */
.rapport-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 2px solid #f1f5f9;
}

.rapport-date {
    font-size: 0.8rem;
    color: #6b7280;
}

/* Chargement */
.loading-skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 8px;
    height: 1rem;
    margin-bottom: 0.5rem;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>

<div class="rapports-index-container">
    <!-- En-tête -->
    <div class="rapports-header">
        <h1 class="rapports-title">📋 Mes Rapports</h1>
        <a href="{{ route('rapports.create') }}" class="btn-new-rapport">
            <i class="fas fa-plus"></i> Nouveau Rapport
        </a>
    </div>

    <!-- Grille des rapports -->
    @if($rapports->count() > 0)
        <div class="rapports-grid">
            @foreach($rapports as $rapport)
            <div class="rapport-card">
                <!-- En-tête carte -->
                <div class="rapport-card-header">
                    <h3 class="rapport-card-title">{{ $rapport->titre }}</h3>
                    <span class="rapport-card-type type-{{ $rapport->type }}">
                        {{ $rapport->type }}
                    </span>
                </div>

                <!-- Période -->
                <div class="rapport-card-dates">
                    📅 du {{ $rapport->date_debut->format('d/m/Y') }} 
                    au {{ $rapport->date_fin->format('d/m/Y') }}
                </div>

                <!-- Mini statistiques -->
                <div class="rapport-card-stats">
                    <div class="stat-mini">
                        <div class="stat-mini-number">{{ $rapport->donnees['statistiques']['total_demandes'] ?? 0 }}</div>
                        <div class="stat-mini-label">Total</div>
                    </div>
                    <div class="stat-mini">
                        <div class="stat-mini-number">{{ $rapport->donnees['statistiques']['approuvees'] ?? 0 }}</div>
                        <div class="stat-mini-label">Approuvées</div>
                    </div>
                    <div class="stat-mini">
                        <div class="stat-mini-number">{{ $rapport->donnees['statistiques']['rejetees'] ?? 0 }}</div>
                        <div class="stat-mini-label">Rejetées</div>
                    </div>
                    <div class="stat-mini">
                        <div class="stat-mini-number">{{ $rapport->donnees['statistiques']['en_attente'] ?? 0 }}</div>
                        <div class="stat-mini-label">En Attente</div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="rapport-card-actions">
                    <a href="{{ route('rapports.show', $rapport) }}" class="btn-action btn-view">
                        <i class="fas fa-eye"></i> Voir
                    </a>
                    <a href="{{ route('rapports.pdf', $rapport) }}" class="btn-action btn-pdf">
                        <i class="fas fa-download"></i> PDF
                    </a>
                    <form action="{{ route('rapports.destroy', $rapport) }}" method="POST" style="margin:0;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-action btn-delete" 
                                onclick="return confirm('Supprimer ce rapport définitivement?')">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                </div>

                <!-- Footer -->
                <div class="rapport-card-footer">
                    <span class="rapport-date">
                        Créé le {{ $rapport->created_at->format('d/m/Y') }}
                    </span>
                    @if($rapport->description)
                    <span class="text-sm text-gray-500" title="{{ $rapport->description }}">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($rapports->hasPages())
        <div class="pagination-container">
            {{ $rapports->links() }}
        </div>
        @endif

    @else
        <!-- État vide -->
        <div class="empty-state">
            <div class="empty-state-icon">📊</div>
            <h3 class="empty-state-title">Aucun rapport généré</h3>
            <p class="empty-state-text">
                Vous n'avez pas encore créé de rapport. Commencez par générer votre premier rapport pour analyser les demandes de congés.
            </p>
            <a href="{{ route('rapports.create') }}" class="btn-new-rapport">
                <i class="fas fa-plus"></i> Créer mon premier rapport
            </a>
        </div>
    @endif
</div>

<script>
// Animation au défilement
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.rapport-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>
@endsection