@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4">🔔 Envoyer une notification</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('notifications.envoyer') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="message" class="block text-sm font-medium">Message de la notification</label>
            <textarea name="message" id="message" rows="4" required
                class="w-full border rounded p-2 mt-1 focus:ring focus:ring-blue-300"></textarea>
        </div>

        <button type="submit" 
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Envoyer à tout le personnel
        </button>
    </form>
</div>
@endsection
