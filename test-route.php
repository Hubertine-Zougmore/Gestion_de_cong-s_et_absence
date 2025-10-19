<?php
// Dans la racine de votre projet
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

try {
    echo "Test des models...\n";
    $user = new App\Models\User();
    echo "✅ User OK\n";
    
    $role = new App\Models\Role();
    echo "✅ Role OK\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}