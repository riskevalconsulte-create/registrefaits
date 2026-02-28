<?php
require_once 'auth.php';
global $pdo;

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);

    if (!empty($titre)) {

        $stmt = $pdo->prepare("
            INSERT INTO fiches (user_id, titre, description, statut, date_creation)
            VALUES (?, ?, ?, 'brouillon', NOW())
        ");
        $stmt->execute([$user_id, $titre, $description]);

        header("Location: dashboard.php");
        exit;

    } else {
        $message = "Le titre est obligatoire.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle fiche</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">

<h1>Créer une nouvelle fiche</h1>

<?php if (!empty($message)): ?>
    <div class="alert alert-danger"><?= $message ?></div>
<?php endif; ?>

<form method="post" class="mt-4 col-md-6">

    <div class="mb-3">
        <label class="form-label">Titre *</label>
        <input type="text" name="titre" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="5"></textarea>
    </div>

    <button class="btn btn-success">Enregistrer la fiche</button>
    <a href="dashboard.php" class="btn btn-secondary">Annuler</a>

</form>

</body>
</html>
