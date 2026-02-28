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
$stmt = $pdo->prepare("
    SELECT f.*, u.email 
    FROM fiches f
    JOIN users u ON f.user_id = u.id
    WHERE f.id = ?
");
$stmt->execute([$fiche_id]);
$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    die("Cette fiche n'existe pas.");
}

// Sécurité : un utilisateur ne peut voir que ses fiches, sauf admin
if ($role !== 'admin' && $fiche['user_id'] != $user_id) {
    die("Accès refusé.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche #<?= $fiche['id'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">

<h1>Fiche #<?= $fiche['id'] ?></h1>

<div class="card mt-4">
    <div class="card-body">

        <h3><?= htmlspecialchars($fiche['titre']) ?></h3>

        <p class="text-muted">
            Créée le <?= $fiche['date_creation'] ?>  
            <br>
            Auteur : <?= htmlspecialchars($fiche['email']) ?>
            <br>
            Statut : <strong><?= htmlspecialchars($fiche['statut']) ?></strong>
        </p>

        <hr>

        <p><?= nl2br(htmlspecialchars($fiche['description'])) ?></p>

    </div>
</div>

<p class="mt-4">
    <a href="dashboard.php" class="btn btn-secondary">Retour au tableau de bord</a>

    <?php if ($role === 'admin' || $fiche['user_id'] == $user_id): ?>
        <a href="fiche_edit.php?id=<?= $fiche['id'] ?>" class="btn btn-warning">Modifier</a>
        <a href="fiche_delete.php?id=<?= $fiche['id'] ?>" class="btn btn-danger"
           onclick="return confirm('Supprimer cette fiche ?');">Supprimer</a>
    <?php endif; ?>
</p>

</body>
</html>
