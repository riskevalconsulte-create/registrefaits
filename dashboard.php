<?php
require_once 'auth.php';
global $pdo;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Récupération des fiches
if ($role === 'admin') {
    $stmt = $pdo->query("SELECT * FROM fiches_tiers ORDER BY id DESC");
} else {
    $stmt = $pdo->prepare("SELECT * FROM fiches_tiers WHERE user_id = ? ORDER BY id DESC");
    $stmt->execute([$user_id]);
}

$fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard – Faits de tiers</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<style>
/* DESIGN GLOBAL */
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

.table {
    background: white;
    border-radius: var(--radius);
    overflow: hidden;
}

.table thead {
    background: var(--primary);
    color: white;
}

.table th {
    padding: 14px;
    font-size: 15px;
}

.table td {
    padding: 12px;
    vertical-align: middle;
}

.badge {
    padding: 8px 12px;
    border-radius: var(--radius);
    font-size: 13px;
}

.badge-success { background: var(--accent); }
.badge-danger { background: var(--danger); }
.badge-warning { background: var(--warning); color: black; }

.btn {
    border-radius: var(--radius);
    padding: 8px 14px;
    font-weight: 600;
}

.btn-primary { background: var(--primary); border: none; }
.btn-primary:hover { background: #004A8F; }

.btn-secondary { border: none; }

.action-buttons a {
    margin-right: 6px;
}
</style>
</head>

<body class="container py-5">

<div class="card p-4">

<h1>Tableau de bord – Faits de tiers</h1>

<div class="d-flex justify-content-between mb-3">
    <div></div>
    <a href="fiche_nouvelle_travailleur.php" class="btn btn-primary">Nouvelle fiche</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <?php if ($role === 'admin'): ?>
                <th>#</th>
            <?php endif; ?>
            <th>Date</th>
            <th>Travailleur</th>
            <th>Type de tiers</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($fiches as $fiche): ?>
        <tr>
            <?php if ($role === 'admin'): ?>
                <td><?= $fiche['id'] ?></td>
            <?php endif; ?>

            <td><?= htmlspecialchars($fiche['date_incident']) ?></td>

            <td><?= htmlspecialchars($fiche['travailleur_nom']) ?> <?= htmlspecialchars($fiche['travailleur_prenom']) ?></td>

            <td><?= htmlspecialchars($fiche['tiers_type']) ?></td>

            <td>
                <?php if ($fiche['statut'] === 'validée'): ?>
                    <span class="badge badge-success">Validée</span>
                <?php elseif ($fiche['statut'] === 'refusée'): ?>
                    <span class="badge badge-danger">Refusée</span>
                <?php else: ?>
                    <span class="badge badge-warning">En attente</span>
                <?php endif; ?>
            </td>

            <td class="action-buttons">
                <a href="fiche_view_tiers.php?id=<?= $fiche['id'] ?>" class="btn btn-secondary btn-sm">Voir</a>

                <?php if ($role === 'admin' || $fiche['user_id'] == $user_id): ?>
                    <a href="fiche_edit_tiers.php?id=<?= $fiche['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                <?php endif; ?>

                <?php if ($role === 'admin'): ?>
                    <a href="fiche_delete_tiers.php?id=<?= $fiche['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette fiche ?');">Supprimer</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>

</body>
</html>
