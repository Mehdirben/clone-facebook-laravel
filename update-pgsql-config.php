<?php

echo "Mise à jour de la configuration PostgreSQL pour Laravel...\n\n";

// Vérifier les identifiants PostgreSQL
try {
    $host = '127.0.0.1';
    $port = '5432';
    $username = 'postgres';
    $password = '000000';
    
    // Tester la connexion au serveur
    $dsn = "pgsql:host=$host;port=$port;dbname=postgres";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connexion au serveur PostgreSQL réussie!\n";
    
    // Vérifier si la base de données facebook_clone existe
    $result = $pdo->query("SELECT 1 FROM pg_database WHERE datname = 'facebook_clone'");
    $exists = $result->fetchColumn();
    
    if (!$exists) {
        echo "La base de données 'facebook_clone' n'existe pas, création en cours...\n";
        $pdo->exec("CREATE DATABASE facebook_clone");
        echo "✓ Base de données 'facebook_clone' créée avec succès!\n";
    } else {
        echo "✓ La base de données 'facebook_clone' existe déjà.\n";
    }
    
    // Mise à jour du fichier .env
    $envFile = '.env';
    if (file_exists($envFile)) {
        $envContent = file_get_contents($envFile);
        
        // Mettre à jour les paramètres
        $replacements = [
            '/DB_CONNECTION=.*/' => 'DB_CONNECTION=pgsql',
            '/DB_HOST=.*/' => 'DB_HOST=127.0.0.1',
            '/DB_PORT=.*/' => 'DB_PORT=5432',
            '/DB_DATABASE=.*/' => 'DB_DATABASE=facebook_clone',
            '/DB_USERNAME=.*/' => 'DB_USERNAME=postgres',
            '/DB_PASSWORD=.*/' => 'DB_PASSWORD=000000'
        ];
        
        foreach ($replacements as $pattern => $replacement) {
            $envContent = preg_replace($pattern, $replacement, $envContent);
        }
        
        file_put_contents($envFile, $envContent);
        echo "✓ Fichier .env mis à jour avec succès!\n";
    } else {
        echo "❌ Fichier .env non trouvé!\n";
    }
    
    // Vider le cache de configuration Laravel
    echo "\nNettoyage du cache Laravel...\n";
    
    if (file_exists('bootstrap/cache/config.php')) {
        unlink('bootstrap/cache/config.php');
        echo "✓ Cache de configuration supprimé.\n";
    }
    
    echo "\nConfiguration PostgreSQL mise à jour avec succès!\n";
    echo "Essayez maintenant d'exécuter: php artisan config:clear && php artisan migrate:fresh\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion à PostgreSQL: " . $e->getMessage() . "\n";
    echo "Veuillez vérifier que PostgreSQL est installé et en cours d'exécution avec les identifiants corrects.\n";
} 