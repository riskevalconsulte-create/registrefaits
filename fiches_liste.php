<?php
require_once 'auth.php';
require_login();

$stmt = $pdo->query("SELECT * FROM fiches ORDER BY created_at DESC");
$fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiches enregistrées</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
<h1>Fiches enregistrées</h1>
<a href="dashboard.php" class="btn btn-link">Retour au tableau de bord</a>

<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Date incident</th>
            <th>Description</th>
            <th>Créée le</th>
            <th>PDF</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($fiches as $f): ?>
        <tr>
            <td><?= $f['id'] ?></td>
            <td><?= htmlspecialchars($f['date_incident']) ?></td>
            <td><?= htmlspecialchars(mb_substr($f['description'], 0, 80)) ?>...</td>
            <td><?= $f['created_at'] ?></td>
            <td><a href="export_pdf.php?id=<?= $f['id'] ?>" class="btn btn-sm btn-outline-secondary">PDF</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
