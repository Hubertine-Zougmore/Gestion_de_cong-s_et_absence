{{-- resources/views/admin/settings/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Paramètres Système')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Paramètres de l'Application</h3>
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                <a class="nav-link active" href="#general" data-toggle="pill">Général</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#leave-types" data-toggle="pill">Types de congés</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#quotas" data-toggle="pill">Quotas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#periods" data-toggle="pill">Périodes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#notifications" data-toggle="pill">Notifications</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#dashboard" data-toggle="pill">Suivi</a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="tab-content">
                        {{-- ONGLET PARAMÈTRES GÉNÉRAUX --}}
                        <div class="tab-pane active" id="general">
                            <h4>Paramètres Généraux</h4>
                            <form action="{{ route('admin.settings.general') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_name">Nom de l'entreprise</label>
                                            <input type="text" class="form-control" id="company_name" name="company_name" 
                                                   value="{{ App\Models\SystemSetting::getValue('company_name', 'Mon Entreprise') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="max_leave_days_per_year">Jours de congés par an (défaut)</label>
                                            <input type="number" class="form-control" id="max_leave_days_per_year" 
                                                   name="max_leave_days_per_year" 
                                                   value="{{ App\Models\SystemSetting::getValue('max_leave_days_per_year', 25) }}" 
                                                   min="1" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="advance_notice_days">Préavis minimum (jours)</label>
                                            <input type="number" class="form-control" id="advance_notice_days" 
                                                   name="advance_notice_days" 
                                                   value="{{ App\Models\SystemSetting::getValue('advance_notice_days', 7) }}" 
                                                   min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" id="auto_approve_requests" 
                                                       name="auto_approve_requests" 
                                                       {{ App\Models\SystemSetting::getValue('auto_approve_requests', false) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="auto_approve_requests">
                                                    Approbation automatique
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="weekend_included" 
                                                       name="weekend_included" 
                                                       {{ App\Models\SystemSetting::getValue('weekend_included', false) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="weekend_included">
                                                    Inclure les week-ends
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer
                                </button>
                            </form>
                        </div>

                        {{-- ONGLET TYPES DE CONGÉS --}}
                        <div class="tab-pane" id="leave-types">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4>Types de Congés</h4>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLeaveTypeModal">
                                    <i class="fas fa-plus"></i> Ajouter un type
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Code</th>
                                            <th>Jours max/an</th>
                                            <th>Préavis (j)</th>
                                            <th>Mois autorisés</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($leaveTypes as $type)
                                        <tr>
                                            <td>{{ $type->name }}</td>
                                            <td><span class="badge badge-secondary">{{ $type->code }}</span></td>
                                            <td>{{ $type->max_days_per_year }}</td>
                                            <td>{{ $type->advance_notice_days }}</td>
                                            <td>
                                                @if($type->allowed_months)
                                                    @foreach($type->allowed_months as $month)
                                                        <span class="badge badge-info">{{ $month }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">Tous</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $type->is_active ? 'success' : 'danger' }}">
                                                    {{ $type->is_active ? 'Actif' : 'Inactif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning edit-leave-type" 
                                                        data-id="{{ $type->id }}" data-toggle="modal" 
                                                        data-target="#editLeaveTypeModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ONGLET GESTION DES QUOTAS --}}
                        <div class="tab-pane" id="quotas">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4>Gestion des Quotas Utilisateurs</h4>
                                <div>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#bulkAssignModal">
                                        <i class="fas fa-users"></i> Attribution en masse
                                    </button>
                                    <a href="{{ route('admin.user-quotas') }}" class="btn btn-primary">
                                        <i class="fas fa-cog"></i> Gérer les quotas
                                    </a>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Utilisez cette section pour attribuer des quotas de congés spécifiques à chaque employé 
                                selon les différents types de congés.
                            </div>
                        </div>

                        {{-- ONGLET PÉRIODES DE CONGÉS --}}
                        <div class="tab-pane" id="periods">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4>Périodes de Congés Autorisées</h4>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPeriodModal">
                                    <i class="fas fa-plus"></i> Ajouter une période
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Date début</th>
                                            <th>Date fin</th>
                                            <th>Statut</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($leavePeriods as $period)
                                        <tr>
                                            <td>{{ $period->name }}</td>
                                            <td>{{ $period->start_date->format('d/m/Y') }}</td>
                                            <td>{{ $period->end_date->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $period->is_active ? 'success' : 'danger' }}">
                                                    {{ $period->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                                @if($period->isCurrentPeriod())
                                                    <span class="badge badge-primary">En cours</span>
                                                @endif
                                            </td>
                                            <td>{{ Str::limit($period->description, 50) }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ONGLET NOTIFICATIONS --}}
                        <div class="tab-pane" id="notifications">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4>Notifications Programmées</h4>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNotificationModal">
                                    <i class="fas fa-plus"></i> Programmer une notification
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Titre</th>
                                            <th>Type</th>
                                            <th>Date d'envoi</th>
                                            <th>Heure</th>
                                            <th>Destinataires</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($notifications as $notification)
                                        <tr>
                                            <td>{{ $notification->title }}</td>
                                            <td>
                                                <span class="badge badge-{{ $notification->type === 'reminder' ? 'info' : ($notification->type === 'deadline' ? 'warning' : 'primary') }}">
                                                    {{ ucfirst($notification->type) }}
                                                </span>
                                            </td>
                                            <td>{{ $notification->send_date->format('d/m/Y') }}</td>
                                            <td>{{ $notification->send_time->format('H:i') }}</td>
                                            <td>
                                                @foreach($notification->recipient_roles as $role)
                                                    <span class="badge badge-secondary">{{ ucfirst($role) }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $notification->is_sent ? 'success' : 'warning' }}">
                                                    {{ $notification->is_sent ? 'Envoyée' : 'En attente' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if(!$notification->is_sent)
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ONGLET TABLEAU DE BORD --}}
                        <div class="tab-pane" id="dashboard">
                            <h4>Tableau de Bord d'Exécution</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Employés</span>
                                            <span class="info-box-number">{{ App\Models\User::role('agent')->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="fas fa-calendar"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Types de congés actifs</span>
                                            <span class="info-box-number">{{ $leaveTypes->where('is_active', true)->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Notifications en attente</span>
                                            <span class="info-box-number">{{ $notifications->where('is_sent', false)->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Demandes en attente</span>
                                            <span class="info-box-number">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Utilisation des Congés par Type ({{ date('Y') }})</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="leaveUsageChart" height="100"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAUX --}}
@include('admin.settings.modals.add-leave-type')
@include('admin.settings.modals.edit-leave-type')
@include('admin.settings.modals.add-period')
@include('admin.settings.modals.add-notification')
@include('admin.settings.modals.bulk-assign')

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique d'utilisation des congés
const ctx = document.getElementById('leaveUsageChart').getContext('2d');
const leaveUsageChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($leaveTypes->pluck('name')) !!},
        datasets: [{
            label: 'Jours alloués',
            data: [25, 15, 90, 30], // À remplacer par des données réelles
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }, {
            label: 'Jours utilisés',
            data: [12, 3, 0, 5], // À remplacer par des données réelles
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection