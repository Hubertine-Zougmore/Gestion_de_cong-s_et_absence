{{-- resources/views/users/create.blade.php --}}

@extends('layouts.app')

@section('content')
<h1>Créer un nouvel utilisateur</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <label for="nom">Nom :</label>
    <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required>

    <label for="prenom">Prénom :</label>
    <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required>

    <label for="email">Email :</label>
    <input type="email" name="email" id="email" value="{{ old('email') }}" required>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" required>

    <label for="password_confirmation">Confirmer mot de passe :</label>
    <input type="password" name="password_confirmation" id="password_confirmation" required>

    {{-- Ajoute ici d’autres champs selon ton modèle --}}

    <button type="submit">Créer</button>
</form>
@endsection
