{{-- resources/views/users/index.blade.php --}}

@extends('layouts.app')

@section('content')
<h1>Liste des utilisateurs</h1>

<a href="{{ route('users.create') }}">Créer un nouvel utilisateur</a>

<table>
    <thead>
        <tr>
            <th>Nom complet</th>
            <th>Email</th>
            <th>Rôle(s)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->prenom }} {{ $user->nom }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                <td>
                    <a href="{{ route('users.edit', $user) }}">Modifier</a>
                    {{-- Formulaire suppression --}}
                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
