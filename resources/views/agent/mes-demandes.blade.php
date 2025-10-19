@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Mes Demandes de Congé</h2>
    
    @if($demandes->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($demandes as $demande)
                        <tr>
                            <td>{{ $demande->type_conge }}</td>
                            <td>{{ $demande->date_debut->format('d/m/Y') }}</td>
                            <td>{{ $demande->date_fin->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge 
                                    @if($demande->statut === 'approuve') badge-success
                                    @elseif($demande->statut === 'rejete') badge-danger
                                    @else badge-warning @endif">
                                    {{ ucfirst($demande->statut) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('agent.mes-demandes.show', $demande) }}" 
                                   class="btn btn-sm btn-info">Voir</a>
                                
                                @can('update', $demande)
                                    <a href="{{ route('agent.mes-demandes.edit', $demande) }}" 
                                       class="btn btn-sm btn-warning">Modifier</a>
                                @endcan
                                
                                @can('delete', $demande)
                                    <form method="POST" 
                                          action="{{ route('agent.mes-demandes.delete', $demande) }}" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Êtes-vous sûr ?')">
                                            Supprimer
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            Vous n'avez aucune demande de congé.
            <a href="{{ route('agent.mes-demandes.create') }}" class="btn btn-primary">
                Faire une demande
            </a>
        </div>
    @endif
</div>
@endsection