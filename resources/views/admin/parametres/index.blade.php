@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Gestion des paramètres</h2>
    <h2 class="text-xl font-semibold mb-4">Liste des paramètres</h2>
    
    @if (session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.parametres.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            + Ajouter un paramètre
        </a>
    </div>
    
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left font-medium">code</th>
                    <th class="px-6 py-3 text-left font-medium">Valeur</th>
                    <th class="px-6 py-3 text-left font-medium">Description</th>
                    <th class="px-6 py-3 text-left font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($parametres as $parametre)
                    <tr>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $parametre->code}}</td>
                        <td class="px-6 py-4">
                            <code class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-mono">
                                {{ $parametre->valeur }}
                            </code>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $parametre->description ?? 'Aucune description' }}
                        </td>
                        <td class="px-6 py-4 space-x-3">
                            <a href="{{ route('admin.parametres.edit', $parametre->id) }}" 
                               class="text-blue-600 hover:underline">
                                Modifier
                            </a>
                            <form action="{{ route('admin.parametres.destroy', $parametre->id) }}" 
                                  method="POST" 
                                  class="inline-block" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce paramètre ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-900 mb-2">Aucun paramètre configuré</p>
                                <p class="text-gray-500 mb-4">Commencez par créer votre premier paramètre système.</p>
                                <a href="{{ route('admin.parametres.create') }}" 
                                   class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Créer le premier paramètre
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection