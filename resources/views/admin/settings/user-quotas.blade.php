{{-- resources/views/admin/settings/user-quotas.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gestion des Quotas Utilisateurs')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gestion des Quotas par Utilisateur</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addQuotaModal">
                            <i class="fas fa-plus"></i> Ajouter un quota
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="year_filter">Année</label>
                            <select class="form-control" id="year_filter">
                                <option value="{{ $currentYear }}">{{ $currentYear }}</option>
                                <option value="{{ $currentYear + 1 }}">{{ $currentYear + 1 }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="leave_type_filter">Type de congé</label>
                            <select class="form-control" id="leave_type_filter">
                                <option value="">Tous les types</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="user_filter">Rechercher un utilisateur</label>
                            <input type="text" class="form-control" id="user_filter" 
                                   placeholder="Nom ou email...">
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="quotasTable">
                            <thead>
                                <tr>
                                    <th>Employé</th>
                                    <th>Email</th>
                                    <th>Type de congé</th>
                                    <th>Année</th>
                                    <th>Alloués</th>
                                    <th>Utilisés</th>
                                    <th>En attente</th>
                                    <th>Restants</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    @foreach($user->leaveQuotas as $quota)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center mr-2">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <span>{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $quota->leaveType->name }}</span>
                                        </td>
                                        <td>{{ $quota->year }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $quota->allocated_days }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $quota->used_days > 0 ? 'warning' : 'secondary' }}">
                                                {{ $quota->used_days }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $quota->pending_days > 0 ? 'info' : 'secondary' }}">
                                                {{ $quota->pending_days }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $quota->remaining_days > 0 ? 'success' : 'danger' }}">
                                                {{ $quota->remaining_days }}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning edit-quota" 
                                                    data-quota-id="{{ $quota->id }}"
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}"
                                                    data-leave-type-id="{{ $quota->leave_type_id }}"
                                                    data-leave-type-name="{{ $quota->leaveType->name }}"
                                                    data-allocated-days="{{ $quota->allocated_days }}"
                                                    data-year="{{ $quota->year }}"
                                                    data-toggle="modal" data-target="#editQuotaModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info view-history" 
                                                    data-user-id="{{ $user->id }}"
                                                    data-leave-type-id="{{ $quota->leave_type_id }}"
                                                    data-toggle="modal" data-target="#historyModal">
                                                <i class="fas fa-history"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                    {{-- Afficher les utilisateurs sans quotas --}}
                                    @if($user->leaveQuotas->isEmpty())
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-secondary rounded-circle d-flex align-items-center justify-content-center mr-2">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <span>{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td colspan="7" class="text-muted">
                                            <em>Aucun quota attribué</em>
                                            <button type="button" class="btn btn-sm btn-primary ml-2 assign-quota" 
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}"
                                                    data-toggle="modal" data-target="#addQuotaModal">
                                                <i class="fas fa-plus"></i> Attribuer
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ajouter Quota --}}
<div class="modal fade" id="addQuotaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Quota</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.user-quotas.update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="add_user_id">Employé <span class="text-danger">*</span></label>
                        <select class="form-control" id="add_user_id" name="user_id" required>
                            <option value="">Sélectionnez un employé</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="add_leave_type_id">Type de congé <span class="text-danger">*</span></label>
                        <select class="form-control" id="add_leave_type_id" name="leave_type_id" required>
                            <option value="">Sélectionnez un type</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}" data-default-days="{{ $type->max_days_per_year }}">
                                    {{ $type->name }} ({{ $type->max_days_per_year }} jours max)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_allocated_days">Jours alloués <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="add_allocated_days" 
                                       name="allocated_days" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_year">Année <span class="text-danger">*</span></label>
                                <select class="form-control" id="add_year" name="year" required>
                                    <option value="{{ $currentYear }}">{{ $currentYear }}</option>
                                    <option value="{{ $currentYear + 1 }}">{{ $currentYear + 1 }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Modifier Quota --}}
<div class="modal fade" id="editQuotaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le Quota</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.user-quotas.update') }}" method="POST">
                @csrf
                <input type="hidden" id="edit_user_id" name="user_id">
                <input type="hidden" id="edit_leave_type_id" name="leave_type_id">
                <input type="hidden" id="edit_year" name="year">
                
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Employé:</strong> <span id="edit_user_name"></span><br>
                        <strong>Type de congé:</strong> <span id="edit_leave_type_name"></span><br>
                        <strong>Année:</strong> <span id="edit_year_display"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_allocated_days">Jours alloués <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="edit_allocated_days" 
                               name="allocated_days" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Historique --}}
<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Historique des Congés</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="history_content">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin"></i> Chargement...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-remplissage des jours par défaut lors de la sélection du type de congé
    $('#add_leave_type_id').change(function() {
        var defaultDays = $(this).find('option:selected').data('default-days');
        $('#add_allocated_days').val(defaultDays || '');
    });
    
    // Pré-remplir le modal d'ajout avec un utilisateur spécifique
    $('.assign-quota').click(function() {
        var userId = $(this).data('user-id');
        var userName = $(this).data('user-name');
        
        $('#add_user_id').val(userId).trigger('change');
        $('#addQuotaModal .modal-title').text('Attribuer un quota à ' + userName);
    });
    
    // Pré-remplir le modal de modification
    $('.edit-quota').click(function() {
        var quotaId = $(this).data('quota-id');
        var userId = $(this).data('user-id');
        var userName = $(this).data('user-name');
        var leaveTypeId = $(this).data('leave-type-id');
        var leaveTypeName = $(this).data('leave-type-name');
        var allocatedDays = $(this).data('allocated-days');
        var year = $(this).data('year');
        
        $('#edit_user_id').val(userId);
        $('#edit_leave_type_id').val(leaveTypeId);
        $('#edit_year').val(year);
        $('#edit_allocated_days').val(allocatedDays);
        
        $('#edit_user_name').text(userName);
        $('#edit_leave_type_name').text(leaveTypeName);
        $('#edit_year_display').text(year);
    });
    
    // Filtres de recherche
    $('#user_filter').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $("#quotasTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    
    $('#leave_type_filter').change(function() {
        var value = $(this).val();
        if(value) {
            $("#quotasTable tbody tr").hide();
            $("#quotasTable tbody tr").filter(function() {
                return $(this).find('.badge-info').text().indexOf($('#leave_type_filter option:selected').text()) > -1;
            }).show();
        } else {
            $("#quotasTable tbody tr").show();
        }
    });
    
    // Charger l'historique des congés
    $('.view-history').click(function() {
        var userId = $(this).data('user-id');
        var leaveTypeId = $(this).data('leave-type-id');
        
        $('#history_content').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement...</div>');
        
        // Ici vous pourrez faire un appel AJAX pour charger l'historique
        // $.get('/admin/user-leave-history', {user_id: userId, leave_type_id: leaveTypeId}, function(data) {
        //     $('#history_content').html(data);
        // });
        
        // Simuler des données pour l'exemple
        setTimeout(function() {
            $('#history_content').html(`
                <div class="timeline">
                    <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock"></i> 15/03/2024</span>
                        <h3 class="timeline-header">Congé approuvé</h3>
                        <div class="timeline-body">
                            5 jours de congé annuel du 20/03/2024 au 24/03/2024
                        </div>
                    </div>
                    <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock"></i> 10/02/2024</span>
                        <h3 class="timeline-header">Demande soumise</h3>
                        <div class="timeline-body">
                            3 jours de congé maladie du 12/02/2024 au 14/02/2024
                        </div>
                    </div>
                </div>
            `);
        }, 1000);
    });
});
</script>
@endsection