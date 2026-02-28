<?php
// Démarre la session si pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connexion à la base de données
$host = "sqlXXX.epizy.com";
$dbname = "epiz_12345678_fiches";
$username = "epiz_12345678";
$password = "test1234";


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    die("
    <style>
        body { background:#F5F7FA; font-family:Segoe UI, sans-serif; display:flex; justify-content:center; align-items:center; height:100vh; }
        .error-box {
            background:white; padding:30px; border-radius:12px;
            box-shadow:0 4px 18px rgba(0,0,0,0.08); max-width:450px; text-align:center;
        }
        h1 { color:#D9534F; font-size:24px; margin-bottom:10px; }
        p { color:#6C757D; font-size:16px; }
    </style>

    <div class='error-box'>
        <h1>Erreur de connexion</h1>
        <p>Impossible de se connecter à la base de données.</p>
    </div>
    ");
}

// Fonction de protection des pages
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

// Fonction pour vérifier si admin
function require_admin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        echo "
        <style>
            body { background:#F5F7FA; font-family:Segoe UI, sans-serif; display:flex; justify-content:center; align-items:center; height:100vh; }
            .error-box {
                background:white; padding:30px; border-radius:12px;
                box-shadow:0 4px 18px rgba(0,0,0,0.08); max-width:450px; text-align:center;
            }
            h1 { color:#D9534F; font-size:24px; margin-bottom:10px; }
            p { color:#6C757D; font-size:16px; }
            a {
                display:inline-block; margin-top:15px; padding:10px 18px;
                background:#0057A8; color:white; border-radius:12px; text-decoration:none;
                font-weight:600;
            }
            a:hover { background:#004A8F; }
        </style>

        <div class='error-box'>
            <h1>Accès refusé</h1>
            <p>Vous n'avez pas les droits nécessaires pour accéder à cette page.</p>
            <a href='dashboard.php'>Retour</a>
        </div>
        ";
        exit;
    }
}
?>
