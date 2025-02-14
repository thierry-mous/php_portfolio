<?php
require_once __DIR__ . '/config.php';

try {
    $connexion = new PDO(
        $db_dsn,
        $db_user,
        $db_password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}