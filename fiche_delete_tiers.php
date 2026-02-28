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

// Vérification ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Fiche invalide.");
}

$fiche_id = (int) $_GET['id'];

// Récupération de la fiche
$stmt = $pdo->prepare("SELECT * FROM fiches_tiers WHERE id = ?");
$stmt->execute([$fiche_id]);
$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    die("Cette fiche n'existe pas.");
}

// Seul l'admin peut supprimer
if ($role !== 'admin') {
    die("Accès refusé.");
}

// Suppression si confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM fiches_tiers WHERE id = ?");
    $stmt->execute([$fiche_id]);

    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Supprimer la fiche</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<style>
:root {
    --primary: #0057A8;
    --primary-light: #E8F1FB;
    --danger: #D9534F;
    --danger-light: #FDE2E1;
    --text-dark: #2A2A2A;
    --text-light: #6C757D;
    --radius: 12px;
}

body {
    background: #F5F7FA;
    font-family: "Segoe UI", Roboto, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.delete-card {
    width: 100%;
    max-width: 480px;
    background: white;
    padding: 35px;
    border-radius: var(--radius);
    box-shadow: 0 4px 18px rgba(0,0,0,0.08);
    text-align: center;
}

h1 {
    font-size: 26px;
    font-weight: 700;
    color: var(--danger);
    margin-bottom: 15px;
}

p {
    color: var(--text-light);
    font-size: 17px;
    margin-bottom: 25px;
}

.btn {
    border-radius: var(--radius);
    padding: 10px 18px;
    font-weight: 600;
    width: 100%;
    margin-bottom: 12px;
}

.btn-danger {
    background: var(--danger);
    border: none;
}

.btn-danger:hover {
    background: #C64542;
}

.btn-secondary {
    background: #E2E6EA;
    border: none;
    color: #333;
}

.btn-secondary:hover {
    background: #D6DADF;
}
</style>
</head>

<body>

<div class="delete-card">

    <h1>Supprimer la fiche</h1>

    <p>Êtes-vous sûr de vouloir supprimer cette fiche ?  
    Cette action est <strong>définitive</strong> et ne peut pas être annulée.</p>

    <form method="post">
        <button class="btn btn-danger">Oui, supprimer définitivement</button>
    </form>

    <a href="dashboard.php" class="btn btn-secondary">Annuler</a>

</div>

</body>
</html>
