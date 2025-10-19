<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{                  
    
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    
public function update(ProfileUpdateRequest $request): RedirectResponse
{// Debuggage (optionnel)
       
    $user = $request->user();
    $validated = $request->validated();
//dd($validated);
    // Gestion de la photo
    $this->handleProfilePhoto($request, $user, $validated);

    // Mise à jour de l'utilisateur
    $user->fill($validated);
    
    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
}

protected function handleProfilePhoto($request, $user, &$validated)
{
    if ($request->hasFile('photo')) {
        // Supprimer l'ancienne
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }
        
        // Stocker la nouvelle
        $validated['photo'] = $request->file('photo')->store('profile-photos', 'public');
    }
    
    // Suppression demandée
    if ($request->has('remove_photo') && $user->photo) {
        Storage::disk('public')->delete($user->photo);
        $validated['photo'] = null;
    }
}
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    /**
 * Display the user's profile.
 */
public function show(Request $request): View
{
    return view('profile.show', [
        'user' => $request->user(),
    ]);
}
}
