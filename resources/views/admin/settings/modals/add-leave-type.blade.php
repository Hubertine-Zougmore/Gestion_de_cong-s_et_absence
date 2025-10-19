{{-- resources/views/admin/settings/modals/add-leave-type.blade.php --}}
<div class="modal fade" id="addLeaveTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Type de Congé</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.leave-types.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                        <label>Destinataires <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="role_admin" 
                                   name="recipient_roles[]" value="admin">
                            <label class="form-check-label" for="role_admin">
                                Administrateurs
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="role_agent" 
                                   name="recipient_roles[]" value="agent" checked>
                            <label class="form-check-label" for="role_agent">
                                Agents/Employés
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-clock"></i> Programmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- resources/views/admin/settings/modals/bulk-assign.blade.php --}}
<div class="modal fade" id="bulkAssignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attribution en Masse</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.bulk-quotas') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Cette action attribuera le même quota à tous les employés pour le type de congé sélectionné.
                    </div>
                    
                    <div class="form-group">
                        <label for="bulk_leave_type_id">Type de congé <span class="text-danger">*</span></label>
                        <select class="form-control" id="bulk_leave_type_id" name="leave_type_id" required>
                            <option value="">Sélectionnez un type</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bulk_allocated_days">Jours alloués <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="bulk_allocated_days" 
                                       name="allocated_days" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bulk_year">Année <span class="text-danger">*</span></label>
                                <select class="form-control" id="bulk_year" name="year" required>
                                    <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                    <option value="{{ date('Y') + 1 }}">{{ date('Y') + 1 }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-users"></i> Attribuer à tous
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- resources/views/admin/settings/modals/edit-leave-type.blade.php --}}
<div class="modal fade" id="editLeaveTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le Type de Congé</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editLeaveTypeForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_name">Nom du type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_code">Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_code" name="code" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="2"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_max_days_per_year">Jours max par an <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_max_days_per_year" 
                                       name="max_days_per_year" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_min_days_request">Min jours/demande <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_min_days_request" 
                                       name="min_days_request" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_max_days_request">Max jours/demande</label>
                                <input type="number" class="form-control" id="edit_max_days_request" 
                                       name="max_days_request" min="1">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_advance_notice_days">Préavis (jours) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_advance_notice_days" 
                                       name="advance_notice_days" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_allowed_months">Mois autorisés</label>
                                <select class="form-control" id="edit_allowed_months" name="allowed_months[]" multiple>
                                    <option value="1">Janvier</option>
                                    <option value="2">Février</option>
                                    <option value="3">Mars</option>
                                    <option value="4">Avril</option>
                                    <option value="5">Mai</option>
                                    <option value="6">Juin</option>
                                    <option value="7">Juillet</option>
                                    <option value="8">Août</option>
                                    <option value="9">Septembre</option>
                                    <option value="10">Octobre</option>
                                    <option value="11">Novembre</option>
                                    <option value="12">Décembre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_requires_approval" 
                                       name="requires_approval">
                                <label class="form-check-label" for="edit_requires_approval">
                                    Nécessite une approbation
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_is_paid" name="is_paid">
                                <label class="form-check-label" for="edit_is_paid">
                                    Congé payé
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active">
                                <label class="form-check-label" for="edit_is_active">
                                    Type actif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>group">
                                <label for="name">Nom du type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code">Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="code" name="code" 
                                       placeholder="Ex: ANNUAL, SICK..." required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="max_days_per_year">Jours max par an <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="max_days_per_year" 
                                       name="max_days_per_year" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="min_days_request">Min jours/demande <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="min_days_request" 
                                       name="min_days_request" min="1" value="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="max_days_request">Max jours/demande</label>
                                <input type="number" class="form-control" id="max_days_request" 
                                       name="max_days_request" min="1">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="advance_notice_days">Préavis (jours) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="advance_notice_days" 
                                       name="advance_notice_days" min="0" value="7" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="allowed_months">Mois autorisés</label>
                                <select class="form-control" id="allowed_months" name="allowed_months[]" multiple>
                                    <option value="1">Janvier</option>
                                    <option value="2">Février</option>
                                    <option value="3">Mars</option>
                                    <option value="4">Avril</option>
                                    <option value="5">Mai</option>
                                    <option value="6">Juin</option>
                                    <option value="7">Juillet</option>
                                    <option value="8">Août</option>
                                    <option value="9">Septembre</option>
                                    <option value="10">Octobre</option>
                                    <option value="11">Novembre</option>
                                    <option value="12">Décembre</option>
                                </select>
                                <small class="form-text text-muted">Laissez vide pour tous les mois</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="requires_approval" 
                                       name="requires_approval" checked>
                                <label class="form-check-label" for="requires_approval">
                                    Nécessite une approbation
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_paid" name="is_paid" checked>
                                <label class="form-check-label" for="is_paid">
                                    Congé payé
                                </label>
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

{{-- resources/views/admin/settings/modals/add-period.blade.php --}}
<div class="modal fade" id="addPeriodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une Période de Congés</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.leave-periods.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="period_name">Nom de la période <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="period_name" name="name" 
                               placeholder="Ex: Vacances d'été 2024" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date">Date de début <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date">Date de fin <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="period_description">Description</label>
                        <textarea class="form-control" id="period_description" name="description" 
                                  rows="3" placeholder="Description de la période..."></textarea>
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

{{-- resources/views/admin/settings/modals/add-notification.blade.php --}}
<div class="modal fade" id="addNotificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Programmer une Notification</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.notifications.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="notification_title">Titre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="notification_title" name="title" 
                                       placeholder="Ex: Rappel période de congés" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="notification_type">Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="notification_type" name="type" required>
                                    <option value="reminder">Rappel</option>
                                    <option value="deadline">Échéance</option>
                                    <option value="approval">Approbation</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="notification_message">Message <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="notification_message" name="message" rows="4" 
                                  placeholder="Contenu de la notification..." required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="send_date">Date d'envoi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="send_date" name="send_date" 
                                       min="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="send_time">Heure d'envoi <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="send_time" name="send_time" 
                                       value="09:00" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-