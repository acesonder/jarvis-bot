<?php
/**
 * SQLite Database Setup Script
 * This script creates a SQLite database for development/testing
 */

$dbPath = __DIR__ . '/tweak_easy.db';

// Create SQLite database
$pdo = new PDO("sqlite:$dbPath");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Creating SQLite database at: $dbPath\n\n";

// Read and adapt the MySQL schema
$schema = file_get_contents(__DIR__ . '/schema.sql');

// Convert MySQL syntax to SQLite syntax
$schema = preg_replace('/AUTO_INCREMENT/', 'AUTOINCREMENT', $schema);
$schema = preg_replace('/INT\s+AUTO/', 'INTEGER AUTO', $schema);
$schema = preg_replace('/TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP/', 'TEXT DEFAULT CURRENT_TIMESTAMP', $schema);
$schema = preg_replace('/TIMESTAMP DEFAULT CURRENT_TIMESTAMP/', 'TEXT DEFAULT CURRENT_TIMESTAMP', $schema);
$schema = preg_replace('/TIMESTAMP NULL/', 'TEXT', $schema);
$schema = preg_replace('/ENUM\([^)]+\)/', 'TEXT', $schema);
$schema = preg_replace('/VARCHAR\((\d+)\)/', 'TEXT', $schema);
$schema = preg_replace('/DECIMAL\([^)]+\)/', 'REAL', $schema);
$schema = preg_replace('/TEXT\s+UNIQUE/', 'TEXT', $schema);

// Remove CREATE DATABASE statements
$schema = preg_replace('/CREATE DATABASE.+?;/', '', $schema);
$schema = preg_replace('/USE .+?;/', '', $schema);

// Split into individual statements
$statements = array_filter(
    array_map('trim', explode(';', $schema)),
    function($stmt) { return !empty($stmt) && !preg_match('/^--/', $stmt); }
);

// Execute each statement
foreach ($statements as $statement) {
    if (empty(trim($statement))) continue;
    
    try {
        $pdo->exec($statement);
        // Extract table name for logging
        if (preg_match('/CREATE TABLE\s+(\w+)/i', $statement, $matches)) {
            echo "✓ Created table: {$matches[1]}\n";
        } else if (preg_match('/INSERT INTO\s+(\w+)/i', $statement, $matches)) {
            echo "✓ Inserted data into: {$matches[1]}\n";
        }
    } catch (PDOException $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
        echo "  Statement: " . substr($statement, 0, 100) . "...\n";
    }
}

echo "\n✅ Database setup complete!\n";
echo "Database location: $dbPath\n";
echo "Database size: " . filesize($dbPath) . " bytes\n";
