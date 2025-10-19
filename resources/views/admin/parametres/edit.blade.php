@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier le paramètre</h1>

    <form action="{{ route('admin.parametres.update', $parametre->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="code" class="form-label">Code</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $parametre->code) }}" required>
            @error('code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="valeur" class="form-label">Valeur</label>
            <input type="text" name="valeur" class="form-control" value="{{ old('valeur', $parametre->valeur) }}" required>
            @error('valeur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $parametre->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.parametres.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection