<?php
require_once 'config.php';

if (!isset($_GET['token'])) {
    die("Lien invalide.");
}

$token = $_GET['token'];

$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
$stmt->execute([$token]);
$reset = $stmt->fetch();

if (!$reset) {
    die("Lien invalide ou expiré.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];

    if (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?")
            ->execute([$hash, $reset['email']]);

        $pdo->prepare("DELETE FROM password_resets WHERE email = ?")
            ->execute([$reset['email']]);

        $success = "Mot de passe réinitialisé. Vous pouvez maintenant vous connecter.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser le mot de passe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">
<h1>Réinitialiser le mot de passe</h1>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger mt-3"><?= $error ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success mt-3"><?= $success ?></div>
    <p><a href="login.php" class="btn btn-primary mt-3">Aller à la connexion</a></p>
<?php else: ?>
<form method="post" class="mt-4 col-md-4">
    <div class="mb-3">
        <label class="form-label">Nouveau mot de passe</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button class="btn btn-primary">Mettre à jour le mot de passe</button>
</form>
<?php endif; ?>
</body>
</html>
