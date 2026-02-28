<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$DB_HOST = 'sql202.infinityfree.com';
$DB_NAME = 'if0_41206155_registre';
$DB_USER = 'if0_41206155';
$DB_PASS = 'Jiman290871';

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données.");
}
