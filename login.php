<?php
require_once 'auth.php';
global $pdo;

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        header("Location: dashboard.php");
        exit;

    } else {
        $error = "Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion</title>
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

.login-card {
    width: 100%;
    max-width: 420px;
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

.register-link {
    text-align: center;
    margin-top: 15px;
    color: var(--text-light);
}

.register-link a {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
}

.register-link a:hover {
    text-decoration: underline;
}
</style>
</head>

<body>

<div class="login-card">

    <h1>Connexion</h1>

    <?php if (!empty($error)): ?>
        <div class="error-box"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">

        <label class="mb-1">Adresse e-mail</label>
        <input type="email" name="email" class="form-control mb-3" required>

        <label class="mb-1">Mot de passe</label>
        <input type="password" name="password" class="form-control mb-4" required>

        <button class="btn btn-primary">Se connecter</button>

    </form>

    <div class="register-link">
        Pas encore de compte ?  
        <a href="register.php">Créer un compte</a>
    </div>

</div>

</body>
</html>
