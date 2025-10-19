@extends('layouts.app')

@section('content')
<style>
/* ===== STYLES HARMONISÉS POUR RAPPORTS ===== */
.rapport-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 2rem;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* En-tête */
.rapport-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    padding: 3rem 2rem;
    border-radius: 20px;
    margin-bottom: 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.rapport-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 15s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.rapport-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 2;
}

.rapport-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 2;
}

.rapport-meta {
    font-size: 0.9rem;
    opacity: 0.8;
    margin-top: 1rem;
    position: relative;
    z-index: 2;
}

/* Grille de statistiques */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    border-left: 4px solid #4f46e5;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
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

.stat-card:hover::before {
    transform: scaleX(1);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.icon-wrapper {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.5rem;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

/* Section carte */
.section-card {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    margin-bottom: 2rem;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    border-left: 4px solid #4f46e5;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Tableau */
.rapport-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.rapport-table th {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    padding: 1.2rem 1.5rem;
    text-align: left;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
}

.rapport-table td {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    color: #374151;
}

.rapport-table tr:last-child td {
    border-bottom: none;
}

.rapport-table tr:hover {
    background-color: #f8fafc;
}

/* Barre de progression */
.progress-bar {
    width: 100px;
    height: 8px;
    background: #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
    display: inline-block;
    margin-right: 10px;
    vertical-align: middle;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: 10px;
    transition: width 0.3s ease;
    position: relative;
}

.progress-fill::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.3) 50%, transparent 100%);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.state-positive {
    color: #10b981;
    font-weight: 600;
    font-size: 0.9rem;
}

/* Boutons */
.text-center {
    text-align: center;
}

.no-print {
    margin-top: 3rem;
}

.btn {
    padding: 1rem 2rem;
    border-radius: 15px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 1.1rem;
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

.btn-lg {
    padding: 1.2rem 2.5rem;
    font-size: 1.2rem;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.6s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .rapport-container {
        padding: 1rem;
        margin: 1rem;
    }
    
    .rapport-header {
        padding: 2rem 1rem;
    }
    
    .rapport-title {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .section-card {
        padding: 1.5rem;
    }
    
    .rapport-table {
        font-size: 0.9rem;
    }
    
    .rapport-table th,
    .rapport-table td {
        padding: 0.8rem 1rem;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
        margin-bottom: 1rem;
    }
}

/* Mode impression */
@media print {
    .rapport-container {
        box-shadow: none;
        padding: 0;
        margin: 0;
    }
    
    .no-print {
        display: none !important;
    }
    
    .stat-card, .section-card {
        box-shadow: none;
        border: 1px solid #ddd;
    }
    
    .rapport-header {
        background: #4f46e5 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
/*Pour le format excel*/
.btn-excel {
    background: linear-gradient(135deg, #21a366 0%, #107c41 100%);
    color: white;
}

.btn-excel:hover {
    background: linear-gradient(135deg, #107c41 0%, #0d6635 100%);
    transform: translateY(-2px);
}
</style>

<div class="rapport-container animate-fadeIn">
    <!-- En-tête -->
    <div class="rapport-header">
        <h1 class="rapport-title">{{ $rapport->titre }}</h1>
        <div class="rapport-subtitle">
            📅 Période du {{ $rapport->date_debut->format('d/m/Y') }} au {{ $rapport->date_fin->format('d/m/Y') }}
        </div>
        <div class="rapport-meta">
            📋 Généré le {{ $rapport->created_at->format('d/m/Y à H:i') }} par {{ $rapport->user->name }}
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon-wrapper">📄</div>
            <div class="stat-number">{{ $rapport->donnees['statistiques']['total_demandes'] }}</div>
            <div class="stat-label">Total Demandes</div>
        </div>
        
        <div class="stat-card">
            <div class="icon-wrapper">✅</div>
            <div class="stat-number">{{ $rapport->donnees['statistiques']['approuvees'] }}</div>
            <div class="stat-label">Approuvées</div>
        </div>
        
        <div class="stat-card">
            <div class="icon-wrapper">❌</div>
            <div class="stat-number">{{ $rapport->donnees['statistiques']['rejetees'] }}</div>
            <div class="stat-label">Rejetées</div>
        </div>
        
        <div class="stat-card">
            <div class="icon-wrapper">⏰</div>
            <div class="stat-number">{{ $rapport->donnees['statistiques']['en_attente'] }}</div>
            <div class="stat-label">En Attente</div>
        </div>
    </div>

    <!-- Par type de demande -->
    <div class="section-card">
        <h2 class="section-title">📊 Répartition par Type de Demande</h2>
        <table class="rapport-table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Total</th>
                    <th>Approuvées</th>
                    <th>Taux d'Approvation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rapport->donnees['par_type'] as $type => $data)
                <tr>
                    <td><strong>{{ $type }}</strong></td>
                    <td>{{ $data['total'] }}</td>
                    <td>{{ $data['approuvees'] }}</td>
                    <td>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $data['taux_approbation'] }}%"></div>
                        </div>
                        <span class="state-positive">{{ $data['taux_approbation'] }}%</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Boutons d'action -->
    <div class="text-center no-print">
        <a href="{{ route('rapports.pdf', $rapport) }}" class="btn btn-primary btn-lg">
            <i class="fas fa-download"></i> Télécharger PDF
        </a>
         <a href="{{ route('rapports.excel', $rapport) }}" class="btn btn-success btn-lg">
        <i class="fas fa-file-excel"></i> Télécharger en format Excel
    </a>
        <a href="{{ route('rapports.index') }}" class="btn btn-secondary btn-lg">
            <i class="fas fa-arrow-left"></i> Retour aux Rapports
        </a>
    </div>
</div>
@endsection