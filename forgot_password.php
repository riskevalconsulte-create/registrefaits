<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", time() + 3600);

        $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)")
            ->execute([$email, $token, $expires]);

        $reset_link = "https://registrefaitstiers.infinityfreeapp.com/registre_faits_tiers/reset_password.php?token=$token";

        @mail($email, "Réinitialisation du mot de passe",
            "Cliquez sur ce lien pour réinitialiser votre mot de passe : $reset_link");

        $success = "Un email de réinitialisation a été envoyé (si l'adresse existe).";
    } else {
        $success = "Un email de réinitialisation a été envoyé (si l'adresse existe).";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">
<h1>Mot de passe oublié</h1>
<a href="login.php">Retour à la connexion</a>

<?php if (!empty($success)): ?>
    <div class="alert alert-success mt-3"><?= $success ?></div>
<?php endif; ?>

<form method="post" class="mt-4 col-md-4">
    <div class="mb-3">
        <label class="form-label">Votre adresse email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <button class="btn btn-primary">Envoyer le lien de réinitialisation</button>
</form>
</body>
</html>
