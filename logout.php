<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Déconnexion</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<style>
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

.logout-card {
    width: 100%;
    max-width: 420px;
    background: white;
    padding: 35px;
    border-radius: var(--radius);
    box-shadow: 0 4px 18px rgba(0,0,0,0.08);
    text-align: center;
}

h1 {
    font-size: 26px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 15px;
}

p {
    color: var(--text-light);
    font-size: 16px;
}

.btn-primary {
    background: var(--primary);
    border: none;
    border-radius: var(--radius);
    padding: 10px 18px;
    font-weight: 600;
    width: 100%;
    margin-top: 20px;
}

.btn-primary:hover {
    background: #004A8F;
}
</style>

<!-- Redirection automatique après 3 secondes -->
<meta http-equiv="refresh" content="3;url=login.php">

</head>

<body>

<div class="logout-card">
    <h1>Déconnexion réussie</h1>
    <p>Vous avez été déconnecté de votre compte.</p>
    <p>Redirection vers la page de connexion...</p>

    <a href="login.php" class="btn btn-primary">Retour à la connexion</a>
</div>

</body>
</html>
