<?php

namespace App\Services;

use LdapRecord\Container;
use LdapRecord\Connection;
use LdapRecord\Models\Entry;
use Illuminate\Support\Facades\Log;

class LdapAuthService
{
    private $connection;
    

    public function __construct()
    {
        try {
            $this->connection = Container::getDefaultConnection();
        } catch (\Exception $e) {
            Log::error('Erreur connexion LDAP: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Authentifier un utilisateur contre LDAP
     */
    public function authenticate($username, $password)
    {
        try {
            // 1. Rechercher l'utilisateur dans LDAP
            $ldapUser = $this->findLdapUser($username);
            
            if (!$ldapUser) {
                Log::info("Utilisateur LDAP non trouvé: {$username}");
                return false;
            }

            // 2. Tenter l'authentification avec les credentials
            $userDn = $ldapUser->getDn();
            
            // Créer une nouvelle connexion pour l'auth
            $authConnection = new Connection([
                'hosts' => config('ldap.connections.default.hosts'),
                'port' => config('ldap.connections.default.port'),
                'base_dn' => config('ldap.connections.default.base_dn'),
                'username' => $userDn,
                'password' => $password,
                'use_ssl' => config('ldap.connections.default.use_ssl'),
                'use_tls' => config('ldap.connections.default.use_tls'),
                'version' => config('ldap.connections.default.version'),
            ]);

            // Test de connexion avec les credentials utilisateur
            $isAuthenticated = $authConnection->auth()->attempt($userDn, $password);
            
            Log::info("Authentification LDAP pour {$username}: " . ($isAuthenticated ? 'succès' : 'échec'));
            
            return $isAuthenticated;

        } catch (\Exception $e) {
            Log::error('Erreur authentification LDAP: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Rechercher un utilisateur dans LDAP
     */
    public function findLdapUser($username)
    {
        try {
            $query = $this->connection->query()
                ->setBaseDn('ou=personnes,dc=uts,dc=bf')
                ->where('uid', '=', $username);

            $user = $query->first();
            
            if ($user) {
                Log::info("Utilisateur LDAP trouvé: {$username} - DN: " . $user->getDn());
            }
            
            return $user;

        } catch (\Exception $e) {
            Log::error('Erreur recherche LDAP: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupérer les informations d'un utilisateur LDAP
     */
    public function getLdapUserInfo($username)
    {
        $ldapUser = $this->findLdapUser($username);
        
        if (!$ldapUser) {
            return null;
        }

        try {
            return [
                'uid' => $ldapUser->getFirstAttribute('uid'),
                'cn' => $ldapUser->getFirstAttribute('cn'),
                'givenname' => $ldapUser->getFirstAttribute('givenname') ?: $ldapUser->getFirstAttribute('givenName'),
                'sn' => $ldapUser->getFirstAttribute('sn'),
                'mail' => $ldapUser->getFirstAttribute('mail'),
                'ou' => $ldapUser->getFirstAttribute('ou'),
                'telephonenumber' => $ldapUser->getFirstAttribute('telephonenumber'),
                'title' => $ldapUser->getFirstAttribute('title'),
                'employeestartdate' => $ldapUser->getFirstAttribute('employeestartdate'), // Si disponible dans LDAP
                'whencreated' => $ldapUser->getFirstAttribute('whencreated'), // Date création compte
                'dn' => $ldapUser->getDn(),
            ];
        } catch (\Exception $e) {
            Log::error('Erreur récupération info LDAP: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Tester la connexion LDAP
     */
    public function testConnection()
    {
        try {
            $this->connection->query()
                ->setBaseDn('ou=personnes,dc=uts,dc=bf')
                ->where('uid', '=', '*')
                ->limit(1)
                ->get();
            
            return true;
        } catch (\Exception $e) {
            Log::error('Test connexion LDAP échoué: ' . $e->getMessage());
            return false;
        }
    }
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
                'name' => $ldapInfo['cn'] ?: ($ldapInfo['givenname'] . ' ' . $ldapInfo['sn']),
                'prenom' => $ldapInfo['givenname'],
                'nom' => $ldapInfo['sn'],
                'email' => $ldapInfo['mail'],
                'departement' => $ldapInfo['ou'],
                'telephone' => $ldapInfo['telephonenumber'],
                'poste' => $ldapInfo['title'],
                'date_embauche' => now(), // Date du jour par défaut, à ajuster selon vos besoins
                'statut_actif' => true,
                'password' => Hash::make(Str::random(32)),
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
                // Ne pas modifier date_embauche après création
            ]);
            
            Log::info("Utilisateur mis à jour: {$username}");
        }
        
        return $user;

    } catch (\Exception $e) {
        Log::error('Erreur synchronisation utilisateur: ' . $e->getMessage());
        return null;
    }
}
}