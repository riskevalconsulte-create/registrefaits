<?php
require_once 'auth.php';
require_login();
global $pdo;

if (!isset($_GET['id'])) {
    die("Fiche introuvable.");
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM fiches WHERE id = ?");
$stmt->execute([$id]);
$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    die("Fiche introuvable.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche #<?= $fiche['id'] ?> – PDF</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print { display: none; }
        }
        .fiche-block { margin-bottom: 15px; }
        .fiche-label { font-weight: bold; }
    </style>
</head>
<body class="container py-4">
<div class="no-print mb-3">
    <a href="javascript:window.print()" class="btn btn-primary">Imprimer / Enregistrer en PDF</a>
    <a href="fiches_liste.php" class="btn btn-secondary">Retour</a>
</div>

<h2>Fiche #<?= $fiche['id'] ?></h2>

<div class="fiche-block">
    <span class="fiche-label">Date / heure :</span>
    <?= htmlspecialchars($fiche['date_incident']) ?> – <?= htmlspecialchars($fiche['heure_incident']) ?>
</div>

<div class="fiche-block">
    <span class="fiche-label">Lieu :</span>
    <?= nl2br(htmlspecialchars($fiche['lieu'])) ?>
</div>

<div class="fiche-block">
    <span class="fiche-label">Travailleurs :</span>
    <?= nl2br(htmlspecialchars($fiche['travailleurs'])) ?>
</div>

<div class="fiche-block">
    <span class="fiche-label">Tiers :</span>
    <?= nl2br(htmlspecialchars($fiche['tiers'])) ?>
</div>

<div class="fiche-block">
    <span class="fiche-label">Description :</span><br>
    <?= nl2br(htmlspecialchars($fiche['description'])) ?>
</div>

<div class="fiche-block">
    <span class="fiche-label">Nature :</span>
    <?= nl2br(htmlspecialchars($fiche['nature'])) ?>
</div>

<div class="fiche-block">
    <span class="fiche-label">Conséquences travailleur :</span>
    <?= nl2br(htmlspecialchars($fiche['consequences_travailleur'])) ?>
</div>

<div class="fiche-block">
    <span class="fiche-label">Conséquences entreprise :</span>
    <?= nl2br(htmlspecialchars($fiche['consequences_entreprise'])) ?>
</div>

<div class="fiche-block">
    <span class="fiche-label">Actions :</span>
    <?= nl2br(htmlspecialchars($fiche['actions'])) ?>
</div>

<div class="fiche-block">
    <span class="fiche-label">Mesures :</span>
    <?= nl2br(htmlspecialchars($fiche['mesures'])) ?>
</div>

<div class="fiche-block">
    <span class="fiche-label">Signature travailleur :</span>
    <?= htmlspecialchars($fiche['sign_trav_nom']) ?> (<?= htmlspecialchars($fiche['sign_trav_date']) ?>)
</div>

<div class="fiche-block">
    <span class="fiche-label">Signature responsable :</span>
    <?= htmlspecialchars($fiche['sign_resp_nom']) ?> (<?= htmlspecialchars($fiche['sign_resp_date']) ?>)
</div>

<div class="fiche-block">
    <span class="fiche-label">Créée le :</span>
    <?= htmlspecialchars($fiche['created_at']) ?>
</div>

</body>
</html>
