<?php
require_once 'auth.php';
global $pdo;

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);

    if ($password !== $password2) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {

        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $error = "Un compte existe déjà avec cette adresse e-mail.";
        } else {

            // Création du compte
            $stmt = $pdo->prepare("
                INSERT INTO users (email, password, role)
                VALUES (?, ?, 'travailleur')
            ");

            $stmt->execute([
                $email,
                password_hash($password, PASSWORD_DEFAULT)
            ]);

            $success = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Créer un compte</title>
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
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.register-card {
    width: 100%;
    max-width: 450px;
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
    margin-bottom: 25px;
}

.form-control {
    border-radius: var(--radius);
    border: 1px solid #DDE3EA;
    padding: 10px 14px;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(0,87,168,0.15);
}

.btn-primary {
    background: var(--primary);
    border: none;
    border-radius: var(--radius);
    padding: 10px 18px;
    font-weight: 600;
    width: 100%;
}

.btn-primary:hover {
    background: #004A8F;
}

.error-box {
    background: #FDE2E1;
    color: #B52A2A;
    padding: 10px 14px;
    border-radius: var(--radius);
    margin-bottom: 15px;
    text-align: center;
}

.success-box {
    background: #DFF6E4;
    color: #1B7F4A;
    padding: 10px 14px;
    border-radius: var(--radius);
    margin-bottom: 15px;
    text-align: center;
}

.login-link {
    text-align: center;
    margin-top: 15px;
    color: var(--text-light);
}

.login-link a {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
}

.login-link a:hover {
    text-decoration: underline;
}
</style>
</head>

<body>

<div class="register-card">

    <h1>Créer un compte</h1>

    <?php if (!empty($error)): ?>
        <div class="error-box"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success-box"><?= $success ?></div>
    <?php endif; ?>

    <form method="post">

        <label class="mb-1">Adresse e-mail</label>
        <input type="email" name="email" class="form-control mb-3" required>

        <label class="mb-1">Mot de passe</label>
        <input type="password" name="password" class="form-control mb-3" required>

        <label class="mb-1">Confirmer le mot de passe</label>
        <input type="password" name="password2" class="form-control mb-4" required>

        <button class="btn btn-primary">Créer le compte</button>

    </form>

    <div class="login-link">
        Déjà un compte ?  
        <a href="login.php">Se connecter</a>
    </div>

</div>

</body>
</html>
