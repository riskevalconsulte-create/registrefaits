<?php
require_once 'auth.php';
global $pdo;

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Vérification de l'ID passé en paramètre
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Fiche invalide.");
}

$fiche_id = (int) $_GET['id'];

// Récupération de la fiche
$stmt = $pdo->prepare("SELECT * FROM fiches WHERE id = ?");
$stmt->execute([$fiche_id]);
$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    die("Cette fiche n'existe pas.");
}

// Sécurité : un utilisateur ne peut supprimer que ses fiches, sauf admin
if ($role !== 'admin' && $fiche['user_id'] != $user_id) {
    die("Accès refusé.");
}

// Suppression de la fiche
$stmt = $pdo->prepare("DELETE FROM fiches WHERE id = ?");
$stmt->execute([$fiche_id]);

// Redirection
header("Location: dashboard.php");
exit;
