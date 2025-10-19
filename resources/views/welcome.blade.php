<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue sur la page de gestion des congés et autorisation d'absence de l'UTS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Styles de base -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        
    </style>
</head>
<body>
    <!-- Particules d'arrière-plan -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Header avec logo -->
    

    <div class="header-section fade-in">
        <div class="logo-container">
            <!-- Logo de l'UTS -->
            <img src="{{ asset('images/logo-uts.png') }}" alt="Logo UTS" class="h-12 w-auto">
            <!--<svg class="logo-svg" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
            </svg>-->
        </div>
        <h1 class="university-name">Université Thomas SANKARA</h1>
        <p class="university-subtitle">SCIENCE • INTÉGRITÉ • SOCIÉTÉ</p>
    </div>

    <!-- Container principal -->
    <div class="container welcome-container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="welcome-card fade-in-delay">
                    <h2 class="welcome-title">
                        <i class="fas fa-users-cog me-3" style="color: var(--uts-primary);"></i>
                        Plateforme de Gestion du Personnel
                    </h2>
                    <p class="welcome-description">
                        Accédez à votre espace personnalisé selon votre rôle et gérez efficacement les ressources humaines de l'université. 
                        Une interface moderne et intuitive pour optimiser la gestion des congés et autorisationd'absence administrative.
                    </p>

                    <!-- Boutons d'action -->
                    <div class="buttons-container">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn-custom btn-success-custom">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Tableau de bord
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn-custom btn-primary-custom">
                                    <i class="fas fa-sign-in-alt"></i>
                                    Se connecter
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>

                <!-- Section des fonctionnalités -->
                <div class="features-section fade-in-delay">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h3 class="feature-title">Sécurisé</h3>
                        <p class="feature-description">Authentification LDAP sécurisée et gestion des droits d'accès</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">Tableaux de bord</h3>
                        <p class="feature-description">Visualisation en temps réel des données RH et statistiques</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="feature-title">Responsive</h3>
                        <p class="feature-description">Interface adaptée à tous vos appareils mobiles et desktop</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <select name="role" required>
    <option value="">Sélectionner un rôle</option>
    @foreach(\Spatie\Permission\Models\Role::all() as $role)
        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
    @endforeach
</select>

   

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>