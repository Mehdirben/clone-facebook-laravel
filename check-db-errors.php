<?php

// Inclusion de l'autoloader de Laravel
require __DIR__.'/vendor/autoload.php';

// Chargement des variables d'environnement
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Vérification de la configuration de base de données ===\n\n";

// Vérifier les paramètres de connexion dans le fichier .env
echo "Configuration actuelle (.env) :\n";
echo "DB_CONNECTION: " . env('DB_CONNECTION') . "\n";
echo "DB_HOST: " . env('DB_HOST') . "\n";
echo "DB_PORT: " . env('DB_PORT') . "\n";
echo "DB_DATABASE: " . env('DB_DATABASE') . "\n";
echo "DB_USERNAME: " . env('DB_USERNAME') . "\n";
echo "DB_PASSWORD: " . str_repeat('*', strlen(env('DB_PASSWORD'))) . "\n\n";

// Tester la connexion PostgreSQL directe (PDO)
echo "Test de connexion PDO directe à PostgreSQL :\n";
try {
    $host = env('DB_HOST', '127.0.0.1');
    $port = env('DB_PORT', '5432');
    $database = env('DB_DATABASE', 'facebook_clone');
    $username = env('DB_USERNAME', 'postgres');
    $password = env('DB_PASSWORD', '');
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$database";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connexion PDO réussie\n";
    
    // Vérifier les tables
    $stmt = $pdo->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'public'");
    $tableCount = $stmt->fetchColumn();
    echo "  → Nombre de tables dans la base de données: $tableCount\n\n";
} catch (PDOException $e) {
    echo "✗ Erreur de connexion PDO: " . $e->getMessage() . "\n\n";
}

// Test de connexion avec Laravel
echo "Test de connexion via Laravel (Illuminate\Database) :\n";
try {
    $db = Illuminate\Support\Facades\DB::connection();
    echo "✓ Connexion Laravel réussie\n";
    echo "  → Base de données: " . $db->getDatabaseName() . "\n";
    
    // Vérifier si les migrations ont été exécutées
    $migrationCount = Illuminate\Support\Facades\DB::table('migrations')->count();
    echo "  → Nombre de migrations exécutées: $migrationCount\n\n";
} catch (Exception $e) {
    echo "✗ Erreur de connexion Laravel: " . $e->getMessage() . "\n\n";
    
    // Afficher plus d'informations de débogage
    echo "Informations de débogage supplémentaires:\n";
    echo "Driver de base de données configuré: " . config('database.default') . "\n";
    echo "Configuration complète: " . json_encode(config('database.connections.' . config('database.default')), JSON_PRETTY_PRINT) . "\n\n";
}

// Vérifier que l'extension PostgreSQL est chargée
echo "Vérification des extensions PHP PostgreSQL :\n";
if (extension_loaded('pdo_pgsql')) {
    echo "✓ Extension pdo_pgsql chargée\n";
} else {
    echo "✗ Extension pdo_pgsql NON chargée\n";
}

if (extension_loaded('pgsql')) {
    echo "✓ Extension pgsql chargée\n";
} else {
    echo "✗ Extension pgsql NON chargée\n";
}

echo "\n=== Fin de la vérification ===\n"; 