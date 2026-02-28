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
    SELECT * FROM fiches
    WHERE id = ?
");
$stmt->execute([$fiche_id]);
$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    die("Cette fiche n'existe pas.");
}

// Sécurité : un utilisateur ne peut modifier que ses fiches, sauf admin
if ($role !== 'admin' && $fiche['user_id'] != $user_id) {
    die("Accès refusé.");
}

$message = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $statut = $_POST['statut'];

    if (!empty($titre)) {

        $stmt = $pdo->prepare("
            UPDATE fiches
            SET titre = ?, description = ?, statut = ?, date_modification = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$titre, $description, $statut, $fiche_id]);

        header("Location: fiche_view.php?id=" . $fiche_id);
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
    <title>Modifier la fiche #<?= $fiche['id'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">

<h1>Modifier la fiche #<?= $fiche['id'] ?></h1>

<?php if (!empty($message)): ?>
    <div class="alert alert-danger"><?= $message ?></div>
<?php endif; ?>

<form method="post" class="mt-4 col-md-6">

    <div class="mb-3">
        <label class="form-label">Titre *</label>
        <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($fiche['titre']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($fiche['description']) ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Statut</label>
        <select name="statut" class="form-select">
            <option value="brouillon" <?= $fiche['statut'] === 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
            <option value="publie" <?= $fiche['statut'] === 'publie' ? 'selected' : '' ?>>Publié</option>
            <option value="archive" <?= $fiche['statut'] === 'archive' ? 'selected' : '' ?>>Archivé</option>
        </select>
    </div>

    <button class="btn btn-success">Enregistrer les modifications</button>
    <a href="fiche_view.php?id=<?= $fiche['id'] ?>" class="btn btn-secondary">Annuler</a>

</form>

</body>
</html>
