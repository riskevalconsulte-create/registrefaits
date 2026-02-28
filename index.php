<?php
session_start();

// Si déjà connecté → redirection automatique
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Accueil – Faits de tiers</title>
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

.home-card {
    width: 100%;
    max-width: 520px;
    background: white;
    padding: 40px;
    border-radius: var(--radius);
    box-shadow: 0 4px 18px rgba(0,0,0,0.08);
    text-align: center;
}

h1 {
    font-size: 30px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 15px;
}

p {
    color: var(--text-light);
    font-size: 17px;
    margin-bottom: 30px;
}

.btn {
    border-radius: var(--radius);
    padding: 12px 20px;
    font-weight: 600;
    width: 100%;
    margin-bottom: 12px;
}

.btn-primary {
    background: var(--primary);
    border: none;
}

.btn-primary:hover {
    background: #004A8F;
}

.btn-outline-primary {
    border: 2px solid var(--primary);
    color: var(--primary);
    background: transparent;
}

.btn-outline-primary:hover {
    background: var(--primary);
    color: white;
}
</style>
</head>

<body>

<div class="home-card">

    <h1>Plateforme – Faits de tiers</h1>

    <p>Bienvenue sur l’outil de déclaration et de gestion des faits de tiers.  
    Connectez‑vous pour accéder à votre espace sécurisé.</p>

    <a href="login.php" class="btn btn-primary">Se connecter</a>
    <a href="register.php" class="btn btn-outline-primary">Créer un compte</a>

</div>

</body>
</html>
