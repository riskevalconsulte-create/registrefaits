<?php
require_once 'auth.php';
global $pdo;

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];

// Seul l'admin peut valider/refuser
if ($role !== 'admin') {
    die("Accès refusé.");
}

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

// Traitement validation / refus
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'];
    $commentaire = trim($_POST['commentaire']);

    if ($action === "valider") {
        $statut = "validée";
    } elseif ($action === "refuser") {
        $statut = "refusée";
    } else {
        die("Action invalide.");
    }

    $stmt = $pdo->prepare("
        UPDATE fiches_tiers 
        SET statut = ?, commentaire_admin = ?
        WHERE id = ?
    ");

    $stmt->execute([$statut, $commentaire, $fiche_id]);

    header("Location: fiche_view_tiers.php?id=" . $fiche_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Validation de la fiche</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<style>
:root {
    --primary: #0057A8;
    --primary-light: #E8F1FB;
    --accent: #00A86B;
    --danger: #D9534F;
    --warning: #F0AD4E;
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

.validate-card {
    width: 100%;
    max-width: 520px;
    background: white;
    padding: 35px;
    border-radius: var(--radius);
    box-shadow: 0 4px 18px rgba(0,0,0,0.08);
}

h1 {
    font-size: 26px;
    font-weight: 700;
    color: var(--primary);
    text-align: center;
    margin-bottom: 20px;
}

p {
    color: var(--text-light);
    font-size: 16px;
}

label {
    font-weight: 600;
    color: var(--text-light);
}

textarea {
    border-radius: var(--radius);
    border: 1px solid #DDE3EA;
    padding: 10px 14px;
}

textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(0,87,168,0.15);
}

.btn {
    border-radius: var(--radius);
    padding: 10px 18px;
    font-weight: 600;
    width: 100%;
    margin-bottom: 12px;
}

.btn-success {
    background: var(--accent);
    border: none;
}

.btn-success:hover {
    background: #008F5C;
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

<div class="validate-card">

    <h1>Validation de la fiche</h1>

    <p>Veuillez choisir une action pour cette fiche et ajouter un commentaire si nécessaire.</p>

    <form method="post">

        <label class="mb-2">Commentaire (optionnel)</label>
        <textarea name="commentaire" class="form-control mb-4" rows="4" placeholder="Votre commentaire..."></textarea>

        <button name="action" value="valider" class="btn btn-success">Valider la fiche</button>
        <button name="action" value="refuser" class="btn btn-danger">Refuser la fiche</button>

    </form>

    <a href="fiche_view_tiers.php?id=<?= $fiche_id ?>" class="btn btn-secondary">Retour</a>

</div>

</body>
</html>
