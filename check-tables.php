<?php

// Paramètres de connexion PostgreSQL
$host = '127.0.0.1';
$port = '5432';
$dbname = 'facebook_clone';
$user = 'postgres';
$password = '000000';

echo "Vérification des tables dans la base de données PostgreSQL...\n\n";

try {
    // Connexion à PostgreSQL
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Liste des tables
    $stmt = $pdo->query("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public' ORDER BY tablename");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tables dans la base de données '$dbname':\n";
    foreach ($tables as $i => $table) {
        echo ($i + 1) . ". $table\n";
    }
    echo "\nNombre total de tables: " . count($tables) . "\n";
    
    // Vérification des migrations
    echo "\nVérification de la table des migrations:\n";
    $stmt = $pdo->query("SELECT id, migration, batch FROM migrations ORDER BY id");
    $migrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Migrations enregistrées:\n";
    foreach ($migrations as $migration) {
        echo "ID: {$migration['id']}, Migration: {$migration['migration']}, Batch: {$migration['batch']}\n";
    }
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
} 