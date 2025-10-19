@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Mes Notifications</h1>

    @if($notifications->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
            Aucune notification pour le moment.
        </div>
    @else
        <ul class="space-y-2">
            @foreach($notifications as $notification)
                <li class="bg-gray-100 p-4 rounded border-l-4 border-blue-500">
                    {{ $notification->data['message'] ?? 'Notification sans message' }}
                    <span class="text-sm text-gray-500 block mt-1">{{ $notification->created_at->diffForHumans() }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
