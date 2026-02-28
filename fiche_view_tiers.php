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

// Vérification de l'ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Fiche invalide.");
}

$fiche_id = (int) $_GET['id'];

// Récupération de la fiche
$stmt = $pdo->prepare("
    SELECT f.*, u.email 
    FROM fiches_tiers f
    JOIN users u ON f.user_id = u.id
    WHERE f.id = ?
");
$stmt->execute([$fiche_id]);
$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    die("Cette fiche n'existe pas.");
}

// Sécurité : un travailleur ne peut voir que ses fiches
if ($role !== 'admin' && $fiche['user_id'] != $user_id) {
    die("Accès refusé.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>
    Fiche de renseignement de fait de tiers
    <?php if ($role === 'admin'): ?> #<?= $fiche['id'] ?><?php endif; ?>
</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<style>
/* DESIGN GLOBAL */
:root {
    --primary: #0057A8;
    --primary-light: #E8F1FB;
    --accent: #00A86B;
    --danger: #D9534F;
    --text-dark: #2A2A2A;
    --text-light: #6C757D;
    --radius: 12px;
}

body {
    background: #F5F7FA;
    font-family: "Segoe UI", Roboto, sans-serif;
    color: var(--text-dark);
}

.card {
    border-radius: var(--radius);
    border: none;
    box-shadow: 0 4px 18px rgba(0,0,0,0.08);
    background: white;
}

h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 25px;
}

h3 {
    font-size: 20px;
    font-weight: 600;
    color: var(--primary);
    margin-top: 25px;
}

h4 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-light);
    margin-top: 15px;
}

.data-block {
    background: #FFFFFF;
    padding: 12px 16px;
    border-radius: var(--radius);
    border: 1px solid #E6E6E6;
    margin-bottom: 10px;
}

.section-box {
    background: var(--primary-light);
    padding: 15px 20px;
    border-radius: var(--radius);
    margin-bottom: 20px;
}

hr {
    border: none;
    border-top: 1px solid #E2E6EA;
    margin: 25px 0;
}

label {
    font-weight: 600;
    color: var(--text-light);
}

.btn {
    border-radius: var(--radius);
    padding: 10px 18px;
    font-weight: 600;
}
</style>
</head>

<body class="container py-5">

<div class="card p-4">

<h1>
    Fiche de renseignement de fait de tiers
    <?php if ($role === 'admin'): ?>
        <span class="text-muted">#<?= $fiche['id'] ?></span>
    <?php endif; ?>
</h1>

<div class="section-box">
<h3>1. Complété par</h3>
<p><strong>Nom :</strong> <?= htmlspecialchars($fiche['nom_complet']) ?></p>
<p><strong>Fonction :</strong> <?= htmlspecialchars($fiche['fonction']) ?></p>
<p><strong>Période couverte :</strong> <?= htmlspecialchars($fiche['periode_couverte']) ?></p>
</div>

<div class="section-box">
<h3>A. Informations générales</h3>

<label>Date de l’incident</label>
<div class="data-block"><?= $fiche['date_incident'] ?></div>

<label>Heure</label>
<div class="data-block"><?= $fiche['heure_incident'] ?></div>

<label>Lieu</label>
<div class="data-block">
    <?= htmlspecialchars($fiche['lieu']) ?>
    <?php if ($fiche['lieu'] === 'Autre'): ?>
        (<?= htmlspecialchars($fiche['lieu_autre']) ?>)
    <?php endif; ?>
</div>

<h4>Travailleur concerné</h4>
<div class="data-block"><strong>Nom :</strong> <?= htmlspecialchars($fiche['travailleur_nom']) ?></div>
<div class="data-block"><strong>Prénom :</strong> <?= htmlspecialchars($fiche['travailleur_prenom']) ?></div>
<div class="data-block"><strong>Fonction :</strong> <?= htmlspecialchars($fiche['travailleur_fonction']) ?></div>

<h4>Tiers impliqué</h4>
<div class="data-block"><strong>Nom :</strong> <?= htmlspecialchars($fiche['tiers_nom']) ?></div>
<div class="data-block"><strong>Organisation :</strong> <?= htmlspecialchars($fiche['tiers_organisation']) ?></div>
<div class="data-block">
    <strong>Type :</strong> <?= htmlspecialchars($fiche['tiers_type']) ?>
    <?php if ($fiche['tiers_type'] === 'Autre'): ?>
        (<?= htmlspecialchars($fiche['tiers_autre']) ?>)
    <?php endif; ?>
</div>

<h4>Catégorie d’implication</h4>
<div class="data-block"><?= htmlspecialchars($fiche['tiers_implication']) ?></div>
</div>

<div class="section-box">
<h3>B. Description factuelle</h3>
<div class="data-block"><?= nl2br(htmlspecialchars($fiche['description'])) ?></div>
</div>

<div class="section-box">
<h3>C. Nature du fait</h3>
<div class="data-block">
    <strong>Nature :</strong> <?= htmlspecialchars($fiche['nature_fait']) ?>
    <?php if ($fiche['nature_fait'] === 'Autre'): ?>
        (<?= htmlspecialchars($fiche['nature_autre']) ?>)
    <?php endif; ?>
</div>
</div>

<div class="section-box">
<h3>D. Conséquences constatées</h3>
<div class="data-block">
    <strong>Pour le travailleur :</strong>  
    <?= htmlspecialchars($fiche['consequence_travailleur']) ?>
    <?php if (!empty($fiche['consequence_travailleur_autre'])): ?>
        (<?= htmlspecialchars($fiche['consequence_travailleur_autre']) ?>)
    <?php endif; ?>
</div>
</div>

<div class="section-box">
<h3>E. Actions immédiates prises</h3>
<div class="data-block">
    <strong>Actions :</strong> <?= htmlspecialchars($fiche['actions']) ?>
    <?php if (!empty($fiche['actions_autre'])): ?>
        (<?= htmlspecialchars($fiche['actions_autre']) ?>)
    <?php endif; ?>
</div>

<div class="data-block">
    <strong>Détails :</strong><br>
    <?= nl2br(htmlspecialchars($fiche['actions_details'])) ?>
</div>
</div>

<div class="section-box">
<h3>F. Mesures proposées / recommandations</h3>
<div class="data-block"><?= nl2br(htmlspecialchars($fiche['mesures'])) ?></div>
</div>

<div class="section-box">
<h3>G. Signatures</h3>
<div class="data-block">
    <strong>Travailleur :</strong> <?= htmlspecialchars($fiche['signature_nom']) ?>  
    <br><strong>Date :</strong> <?= $fiche['signature_date'] ?>
</div>

<div class="data-block">
    <strong>Responsable :</strong> <?= htmlspecialchars($fiche['responsable_nom']) ?>  
    <br><strong>Date :</strong> <?= $fiche['responsable_date'] ?>
</div>
</div>

<a href="dashboard.php" class="btn btn-secondary mt-3">Retour</a>

<?php if ($role === 'admin'): ?>
    <hr>
    <h3>Validation</h3>

    <form method="post" action="fiche_valider_tiers.php?id=<?= $fiche['id'] ?>">
        <label>Commentaire (optionnel)</label>
        <textarea name="commentaire" class="form-control mb-2"></textarea>

        <button name="action" value="valider" class="btn btn-success">Valider</button>
        <button name="action" value="refuser" class="btn btn-danger">Refuser</button>
    </form>
<?php endif; ?>

</div>

</body>
</html>
