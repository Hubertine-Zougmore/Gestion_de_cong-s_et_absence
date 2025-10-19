<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LdapAuthService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LdapLoginController extends Controller
{
    private $ldapService;

    public function __construct(LdapAuthService $ldapService)
    {
        $this->ldapService = $ldapService;
    }

    /**
     * Connexion avec LDAP
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = trim($request->username);
        $password = $request->password;

        try {
            // 1. Authentification LDAP
            if ($this->ldapService->authenticate($username, $password)) {
                
                // 2. Synchronisation avec base locale
                $user = $this->synchronizeUser($username);
                
                if (!$user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de la synchronisation utilisateur'
                    ], 500);
                }

                // 3. Connexion Laravel
                Auth::login($user);
                
                // 4. Génération token API
                $token = $user->createToken('auth-token')->plainTextToken;
                
                Log::info("Connexion réussie pour l'utilisateur: {$username}");
                
                return response()->json([
                    'success' => true,
                    'message' => 'Connexion réussie',
                    'user' => [
                        'id' => $user->id,
                        'ldap_username' => $user->ldap_username,
                        'name' => $user->name,
                        'prenom' => $user->prenom,
                        'nom' => $user->nom,
                        'email' => $user->email,
                        'departement' => $user->departement,
                        'poste' => $user->poste,
                        'solde_conges' => $user->solde_conges,
                    ],
                    'token' => $token
                ]);
            }

            Log::warning("Tentative de connexion échouée pour: {$username}");
            
            return response()->json([
                'success' => false,
                'message' => 'Identifiants incorrects'
            ], 401);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la connexion: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur lors de la connexion'
            ], 500);
        }
    }

    /**
     * Synchroniser utilisateur LDAP avec base locale
     */
    private function synchronizeUser($username)
    {
        try {
            // Récupérer infos LDAP
            $ldapInfo = $this->ldapService->getLdapUserInfo($username);
            
            if (!$ldapInfo) {
                Log::error("Impossible de récupérer les infos LDAP pour: {$username}");
                return null;
            }

            // Chercher utilisateur local
            $user = User::where('ldap_username', $username)->first();
            
            if (!$user) {
                // Créer nouvel utilisateur
                $user = User::create([
                    'ldap_username' => $ldapInfo['uid'],
                    'name' => $ldapInfo['cn'] ?: ($ldapInfo['prenom'] . ' ' . $ldapInfo['nom']),
                    'prenom' => $ldapInfo['givenname'],
                    'nom' => $ldapInfo['sn'],
                    'email' => $ldapInfo['mail'],
                    'departement' => $ldapInfo['ou'],
                    'telephone' => $ldapInfo['telephonenumber'],
                    'poste' => $ldapInfo['title'],
                    'solde_conges' => 25, // Solde initial
                    'statut_actif' => true,
                    'password' => Hash::make(Str::random(32)), // Password aléatoire
                ]);
                
                Log::info("Nouvel utilisateur créé: {$username}");
            } else {
                // Mettre à jour infos LDAP uniquement
                $user->update([
                    'name' => $ldapInfo['cn'] ?: ($ldapInfo['givenname'] . ' ' . $ldapInfo['sn']),
                    'prenom' => $ldapInfo['givenname'],
                    'nom' => $ldapInfo['sn'],
                    'email' => $ldapInfo['mail'],
                    'departement' => $ldapInfo['ou'],
                    'telephone' => $ldapInfo['telephonenumber'],
                    'poste' => $ldapInfo['title'],
                    // Ne pas modifier solde_conges, manager_id, etc. (données locales)
                ]);
                
                Log::info("Utilisateur mis à jour: {$username}");
            }
            
            return $user;

        } catch (\Exception $e) {
            Log::error('Erreur synchronisation utilisateur: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        try {
            // Supprimer le token actuel
            $request->user()->currentAccessToken()->delete();
            
            // Déconnexion Laravel
            Auth::logout();
            
            return response()->json([
                'success' => true,
                'message' => 'Déconnexion réussie'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la déconnexion: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la déconnexion'
            ], 500);
        }
    }

    /**
     * Tester la connexion LDAP
     */
    public function testLdapConnection()
    {
        try {
            $isConnected = $this->ldapService->testConnection();
            
            return response()->json([
                'success' => $isConnected,
                'message' => $isConnected ? 'Connexion LDAP OK' : 'Connexion LDAP échouée'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur test LDAP: ' . $e->getMessage()
            ], 500);
        }
    }
}