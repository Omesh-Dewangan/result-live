<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;charset=utf8mb4', 'root', '');
    $pdo->exec('CREATE DATABASE IF NOT EXISTS result_management');
    echo "Database created successfully\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
