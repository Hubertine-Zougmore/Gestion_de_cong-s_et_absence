<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\DemandeNotification;
use Illuminate\Http\Request;
use App\Notifications\NotificationGenerale;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
     

    public function via($notifiable)
    {
        return ['database']; // stocke dans la DB
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message
        ];
    }
     public function index()
    {
        // Récupérer toutes les notifications de l'utilisateur connecté
        $notifications = Auth::user()->notifications;

        return view('notifications.index', compact('notifications'));
    }
    /*public function envoyerAGroupe(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $agents = User::where('role', 'agent')->get();

        foreach ($agents as $agent) {
            $agent->notify(new DemandeNotification($request->message));
        }

        return back()->with('success', 'Notification envoyée à tous les agents.');
        
    }*/
 
public function markAsRead($id)
{
    $notification = auth()->user()->notifications()->where('id', $id)->first();
    if ($notification) {
        $notification->markAsRead();
    }

    // Retourner à la page précédente au lieu d'un JSON
    return redirect()->back()->with('success', 'Notification marquée comme lue.');
}
 public function envoyerATous(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $users = User::all(); // tout le personnel

        foreach ($users as $user) {
            $user->notify(new NotificationGenerale($request->message));
        }

        return back()->with('success', 'Notification envoyée à tout le personnel !');
    }
     public function form()
    {
        return view('notifications.form');
    }
}


