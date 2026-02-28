<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = trim($_POST['db_host']);
    $db_name = trim($_POST['db_name']);
    $db_user = trim($_POST['db_user']);
    $db_pass = trim($_POST['db_pass']);
    $admin_email = trim($_POST['admin_email']);
    $admin_password = $_POST['admin_password'];

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // Création des tables
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                role ENUM('admin','user') NOT NULL DEFAULT 'user',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS password_resets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                token VARCHAR(255) NOT NULL,
                expires_at DATETIME NOT NULL
            );
        ");

        // TODO : ici tu peux recréer tes tables fiches, etc. si besoin

        // Création admin
        $hash = password_hash($admin_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, role) VALUES (?, ?, 'admin')");
        $stmt->execute([$admin_email, $hash]);

        // Écriture de config.php
        $config = "<?php
\$DB_HOST = '" . addslashes($db_host) . "';
\$DB_NAME = '" . addslashes($db_name) . "';
\$DB_USER = '" . addslashes($db_user) . "';
\$DB_PASS = '" . addslashes($db_pass) . "';

try {
    \$pdo = new PDO(\"mysql:host=\$DB_HOST;dbname=\$DB_NAME;charset=utf8mb4\", \$DB_USER, \$DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException \$e) {
    die(\"Erreur de connexion à la base de données.\");
}
";

        file_put_contents(__DIR__ . '/config.php', $config);

        $success = "Installation terminée. Supprimez install.php pour plus de sécurité.";
    } catch (PDOException $e) {
        $error = "Erreur de connexion : " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Installation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">
<h1>Installation</h1>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
    <p><a href="login.php" class="btn btn-primary mt-3">Aller à la connexion</a></p>
<?php else: ?>
<form method="post" class="mt-4 col-md-6">
    <h3>Base de données</h3>
    <div class="mb-3">
        <label class="form-label">Hôte MySQL</label>
        <input type="text" name="db_host" class="form-control" required value="sql202.infinityfree.com">
    </div>
    <div class="mb-3">
        <label class="form-label">Nom de la base</label>
        <input type="text" name="db_name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Utilisateur MySQL</label>
        <input type="text" name="db_user" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Mot de passe MySQL</label>
        <input type="password" name="db_pass" class="form-control">
    </div>

    <h3 class="mt-4">Compte administrateur</h3>
    <div class="mb-3">
        <label class="form-label">Email admin</label>
        <input type="email" name="admin_email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Mot de passe admin</label>
        <input type="password" name="admin_password" class="form-control" required>
    </div>

    <button class="btn btn-success">Installer</button>
</form>
<?php endif; ?>
</body>
</html>
