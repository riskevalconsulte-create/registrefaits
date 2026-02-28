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

// Vérification de l'ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Fiche invalide.");
}

$fiche_id = (int) $_GET['id'];

// Récupération de la fiche
$stmt = $pdo->prepare("SELECT * FROM fiches_tiers WHERE id = ?");
$stmt->execute([$fiche_id]);
$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    die("Cette fiche n'existe pas.");
}

// Sécurité : un travailleur ne peut modifier que ses fiches
if ($role !== 'admin' && $fiche['user_id'] != $user_id) {
    die("Accès refusé.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("
        UPDATE fiches_tiers SET
            nom_complet=?, fonction=?, periode_couverte=?,
            date_incident=?, heure_incident=?, lieu=?, lieu_autre=?,
            travailleur_nom=?, travailleur_prenom=?, travailleur_fonction=?,
            tiers_nom=?, tiers_organisation=?, tiers_type=?, tiers_autre=?,
            tiers_implication=?,
            description=?, nature_fait=?, nature_autre=?,
            consequence_travailleur=?, consequence_travailleur_autre=?,
            actions=?, actions_autre=?, actions_details=?,
            mesures=?, signature_nom=?, signature_date=?,
            responsable_nom=?, responsable_date=?
        WHERE id=?
    ");

    $stmt->execute([
        $_POST['nom_complet'], $_POST['fonction'], $_POST['periode_couverte'],
        $_POST['date_incident'], $_POST['heure_incident'], $_POST['lieu'], $_POST['lieu_autre'],
        $_POST['travailleur_nom'], $_POST['travailleur_prenom'], $_POST['travailleur_fonction'],
        $_POST['tiers_nom'], $_POST['tiers_organisation'], $_POST['tiers_type'], $_POST['tiers_autre'],
        $_POST['tiers_implication'],
        $_POST['description'], $_POST['nature_fait'], $_POST['nature_autre'],
        $_POST['consequence_travailleur'], $_POST['consequence_travailleur_autre'],
        $_POST['actions'], $_POST['actions_autre'], $_POST['actions_details'],
        $_POST['mesures'], $_POST['signature_nom'], $_POST['signature_date'],
        $_POST['responsable_nom'], $_POST['responsable_date'],
        $fiche_id
    ]);

    header("Location: fiche_view_tiers.php?id=" . $fiche_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Modifier fiche – Fait de tiers</title>
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
    color: var(--text-dark);
}

.card {
    border-radius: var(--radius);
    border: none;
    box-shadow: 0 4px 18px rgba(0,0,0,0.08);
    background: white;
}

h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 25px;
}

h3 {
    font-size: 20px;
    font-weight: 600;
    color: var(--primary);
    margin-top: 25px;
}

h4 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-light);
    margin-top: 15px;
}

.form-control,
.form-select,
textarea {
    border-radius: var(--radius);
    border: 1px solid #DDE3EA;
    padding: 10px 14px;
    transition: 0.2s;
}

.form-control:focus,
.form-select:focus,
textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(0,87,168,0.15);
}

.btn {
    border-radius: var(--radius);
    padding: 10px 18px;
    font-weight: 600;
}

.btn-primary {
    background: var(--primary);
    border: none;
}

.btn-primary:hover {
    background: #004A8F;
}

.section-box {
    background: var(--primary-light);
    padding: 15px 20px;
    border-radius: var(--radius);
    margin-bottom: 20px;
}

hr {
    border: none;
    border-top: 1px solid #E2E6EA;
    margin: 25px 0;
}

label {
    font-weight: 600;
    color: var(--text-light);
}
</style>
</head>

<body class="container py-5">

<div class="card p-4">

<h1>Modifier la fiche de renseignement de fait de tiers</h1>

<form method="post">

<div class="section-box">
<h3>1. Complété par</h3>
<div class="row">
    <div class="col-md-4"><label>Nom</label><input class="form-control" name="nom_complet" value="<?= htmlspecialchars($fiche['nom_complet']) ?>"></div>
    <div class="col-md-4"><label>Fonction</label><input class="form-control" name="fonction" value="<?= htmlspecialchars($fiche['fonction']) ?>"></div>
    <div class="col-md-4"><label>Période couverte</label><input class="form-control" name="periode_couverte" value="<?= htmlspecialchars($fiche['periode_couverte']) ?>"></div>
</div>
</div>

<div class="section-box">
<h3>A. Informations générales</h3>
<div class="row">
    <div class="col-md-4"><label>Date</label><input type="date" class="form-control" name="date_incident" value="<?= $fiche['date_incident'] ?>"></div>
    <div class="col-md-4"><label>Heure</label><input type="time" class="form-control" name="heure_incident" value="<?= $fiche['heure_incident'] ?>"></div>
    <div class="col-md-4">
        <label>Lieu</label>
        <select class="form-select" name="lieu">
            <?php
            $lieux = ["Bureau","Salle de sport","Terrain / piste","Vestiaires","Accueil","Parking","Autre"];
            foreach ($lieux as $l) {
                $sel = ($fiche['lieu'] === $l) ? "selected" : "";
                echo "<option $sel>$l</option>";
            }
            ?>
        </select>
        <input class="form-control mt-2" name="lieu_autre" value="<?= htmlspecialchars($fiche['lieu_autre']) ?>" placeholder="Si autre">
    </div>
</div>

<h4>Travailleur concerné</h4>
<div class="row">
    <div class="col-md-4"><label>Nom</label><input class="form-control" name="travailleur_nom" value="<?= htmlspecialchars($fiche['travailleur_nom']) ?>"></div>
    <div class="col-md-4"><label>Prénom</label><input class="form-control" name="travailleur_prenom" value="<?= htmlspecialchars($fiche['travailleur_prenom']) ?>"></div>
    <div class="col-md-4"><label>Fonction</label><input class="form-control" name="travailleur_fonction" value="<?= htmlspecialchars($fiche['travailleur_fonction']) ?>"></div>
</div>

<h4>Tiers impliqué</h4>
<div class="row">
    <div class="col-md-4"><label>Nom</label><input class="form-control" name="tiers_nom" value="<?= htmlspecialchars($fiche['tiers_nom']) ?>"></div>
    <div class="col-md-4"><label>Organisation</label><input class="form-control" name="tiers_organisation" value="<?= htmlspecialchars($fiche['tiers_organisation']) ?>"></div>
    <div class="col-md-4">
        <label>Type</label>
        <select class="form-select" name="tiers_type">
            <?php
            $types = [
                "Clients",
                "Fournisseurs",
                "Public / tiers inconnus",
                "Intervenants externes",
                "Témoins externes",
                "Entreprise cliente",
                "Autres entreprises présentes",
                "Sociétés partenaires",
                "Organismes publics",
                "Autre"
            ];
            foreach ($types as $t) {
                $sel = ($fiche['tiers_type'] === $t) ? "selected" : "";
                echo "<option $sel>$t</option>";
            }
            ?>
        </select>
        <input class="form-control mt-2" name="tiers_autre" value="<?= htmlspecialchars($fiche['tiers_autre']) ?>" placeholder="Si autre">
    </div>
</div>

<h4>Catégorie d’implication</h4>
<select class="form-select" name="tiers_implication">
    <?php
    $implications = [
        "Auteur présumé",
        "Victime externe",
        "Personne interpellée",
        "Personne refusant de coopérer"
    ];
    foreach ($implications as $i) {
        $sel = ($fiche['tiers_implication'] === $i) ? "selected" : "";
        echo "<option $sel>$i</option>";
    }
    ?>
</select>
</div>

<div class="section-box">
<h3>B. Description factuelle</h3>
<textarea class="form-control" rows="5" name="description"><?= htmlspecialchars($fiche['description']) ?></textarea>
</div>

<div class="section-box">
<h3>C. Nature du fait</h3>
<select class="form-select" name="nature_fait">
    <?php
    $natures = [
        "Agression verbale",
        "Agression physique",
        "Intimidation / menace",
        "Comportement inapproprié",
        "Refus d’obtempérer",
        "Dommage matériel",
        "Intrusion",
        "Autre"
    ];
    foreach ($natures as $n) {
        $sel = ($fiche['nature_fait'] === $n) ? "selected" : "";
        echo "<option $sel>$n</option>";
    }
    ?>
</select>
<input class="form-control mt-2" name="nature_autre" value="<?= htmlspecialchars($fiche['nature_autre']) ?>" placeholder="Si autre">
</div>

<div class="section-box">
<h3>D. Conséquences</h3>
<label>Pour le travailleur</label>
<input class="form-control" name="consequence_travailleur" value="<?= htmlspecialchars($fiche['consequence_travailleur']) ?>">
<input class="form-control mt-2" name="consequence_travailleur_autre" value="<?= htmlspecialchars($fiche['consequence_travailleur_autre']) ?>" placeholder="Si autre">
</div>

<div class="section-box">
<h3>E. Actions immédiates</h3>
<input class="form-control" name="actions" value="<?= htmlspecialchars($fiche['actions']) ?>">
<input class="form-control mt-2" name="actions_autre" value="<?= htmlspecialchars($fiche['actions_autre']) ?>" placeholder="Si autre">
<textarea class="form-control mt-2" rows="3" name="actions_details"><?= htmlspecialchars($fiche['actions_details']) ?></textarea>
</div>

<div class="section-box">
<h3>F. Mesures proposées</h3>
<textarea class="form-control" rows="4" name="mesures"><?= htmlspecialchars($fiche['mesures']) ?></textarea>
</div>

<div class="section-box">
<h3>G. Signatures</h3>
<div class="row">
    <div class="col-md-6"><label>Nom travailleur</label><input class="form-control" name="signature_nom" value="<?= htmlspecialchars($fiche['signature_nom']) ?>"></div>
    <div class="col-md-6"><label>Date</label><input type="date" class="form-control" name="signature_date" value="<?= $fiche['signature_date'] ?>"></div>
</div>

<div class="row mt-3">
    <div class="col-md-6"><label>Nom responsable</label><input class="form-control" name="responsable_nom" value="<?= htmlspecialchars($fiche['responsable_nom']) ?>"></div>
    <div class="col-md-6"><label>Date</label><input type="date" class="form-control" name="responsable_date" value="<?= $fiche['responsable_date'] ?>"></div>
</div>
</div>

<button class="btn btn-primary mt-4">Enregistrer les modifications</button>

</form>
</div>

</body>
</html>
