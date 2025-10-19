@extends('layouts.app')

@section('title', 'Planning des congés')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-700">Planning des congés approuvés</h1>

        @if($plannings->isEmpty())
            <p class="text-gray-500">Aucun congé approuvé pour le moment.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nom</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Type de congé</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Début</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Fin</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plannings as $demande)
                            <tr class="border-b">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $demande->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $demande->type }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($demande->date_debut)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                    {{ ucfirst($demande->statut) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
