@extends('layouts.app')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Statistiques</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white shadow rounded-lg p-5">
                <h2 class="text-sm font-semibold text-gray-500">Utilisateurs</h2>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total_users'] }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-5">
                <h2 class="text-sm font-semibold text-gray-500">Demandes de congé</h2>
                <p class="text-3xl font-bold text-green-600">{{ $stats['total_conges'] }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-5">
                <h2 class="text-sm font-semibold text-gray-500">Congés en attente</h2>
                <p class="text-3xl font-bold text-yellow-500">{{ $stats['conges_en_attente'] }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-5">
                <h2 class="text-sm font-semibold text-gray-500">Congés approuvés</h2>
                <p class="text-3xl font-bold text-green-700">{{ $stats['conges_approuves'] }}</p>
            </div>
        </div>
    </div>
@endsection
