<?php

// Paramètres de connexion
$host = '127.0.0.1';
$port = '5432';
$dbname = 'facebook_clone';
$user = 'postgres';
$password = '000000';

try {
    echo "Connexion à PostgreSQL...\n";
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Récupération des tables...\n";
    $query = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name";
    $stmt = $pdo->query($query);
    
    if ($stmt === false) {
        echo "Erreur dans l'exécution de la requête.\n";
        exit(1);
    }
    
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "Aucune table trouvée dans la base de données.\n";
    } else {
        echo "Tables trouvées :\n";
        foreach ($tables as $i => $table) {
            echo ($i + 1) . ". $table\n";
        }
        
        echo "\nNombre total de tables : " . count($tables) . "\n";
    }
    
} catch (PDOException $e) {
    echo "ERREUR PDO : " . $e->getMessage() . "\n";
    echo "Code : " . $e->getCode() . "\n";
} 