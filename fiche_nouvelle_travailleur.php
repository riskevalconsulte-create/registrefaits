<?php
require_once 'auth.php';
global $pdo;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("
        INSERT INTO fiches_tiers (
            user_id, nom_complet, fonction, periode_couverte,
            date_incident, heure_incident, lieu, lieu_autre,
            travailleur_nom, travailleur_prenom, travailleur_fonction,
            tiers_nom, tiers_organisation, tiers_type, tiers_autre,
            tiers_implication,
            description, nature_fait, nature_autre,
            consequence_travailleur, consequence_travailleur_autre,
            actions, actions_autre, actions_details,
            mesures, signature_nom, signature_date,
            responsable_nom, responsable_date
        ) VALUES (
            ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
        )
    ");

    $stmt->execute([
        $user_id,
        $_POST['nom_complet'], $_POST['fonction'], $_POST['periode_couverte'],
        $_POST['date_incident'], $_POST['heure_incident'], $_POST['lieu'], $_POST['lieu_autre'],
        $_POST['travailleur_nom'], $_POST['travailleur_prenom'], $_POST['travailleur_fonction'],
        $_POST['tiers_nom'], $_POST['tiers_organisation'], $_POST['tiers_type'], $_POST['tiers_autre'],
        $_POST['tiers_implication'],
        $_POST['description'], $_POST['nature_fait'], $_POST['nature_autre'],
        $_POST['consequence_travailleur'], $_POST['consequence_travailleur_autre'],
        $_POST['actions'], $_POST['actions_autre'], $_POST['actions_details'],
        $_POST['mesures'], $_POST['signature_nom'], $_POST['signature_date'],
        $_POST['responsable_nom'], $_POST['responsable_date']
    ]);

    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Nouvelle fiche – Fait de tiers</title>
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

<h1>Fiche de renseignement de fait de tiers</h1>

<form method="post">

<div class="section-box">
<h3>1. Complété par</h3>
<div class="row">
    <div class="col-md-4"><label>Nom</label><input class="form-control" name="nom_complet"></div>
    <div class="col-md-4"><label>Fonction</label><input class="form-control" name="fonction"></div>
    <div class="col-md-4"><label>Période couverte</label><input class="form-control" name="periode_couverte"></div>
</div>
</div>

<div class="section-box">
<h3>A. Informations générales</h3>
<div class="row">
    <div class="col-md-4"><label>Date</label><input type="date" class="form-control" name="date_incident"></div>
    <div class="col-md-4"><label>Heure</label><input type="time" class="form-control" name="heure_incident"></div>
    <div class="col-md-4">
        <label>Lieu</label>
        <select class="form-select" name="lieu">
            <option>Bureau</option>
            <option>Salle de sport</option>
            <option>Terrain / piste</option>
            <option>Vestiaires</option>
            <option>Accueil</option>
            <option>Parking</option>
            <option>Autre</option>
        </select>
        <input class="form-control mt-2" name="lieu_autre" placeholder="Si autre">
    </div>
</div>

<h4>Travailleur concerné</h4>
<div class="row">
    <div class="col-md-4"><label>Nom</label><input class="form-control" name="travailleur_nom"></div>
    <div class="col-md-4"><label>Prénom</label><input class="form-control" name="travailleur_prenom"></div>
    <div class="col-md-4"><label>Fonction</label><input class="form-control" name="travailleur_fonction"></div>
</div>

<h4>Tiers impliqué</h4>
<div class="row">
    <div class="col-md-4"><label>Nom</label><input class="form-control" name="tiers_nom"></div>
    <div class="col-md-4"><label>Organisation</label><input class="form-control" name="tiers_organisation"></div>
    <div class="col-md-4">
        <label>Type</label>
        <select class="form-select" name="tiers_type">
            <option value="Clients">Clients</option>
            <option value="Fournisseurs">Fournisseurs</option>
            <option value="Public / tiers inconnus">Public / tiers inconnus</option>
            <option value="Intervenants externes">Intervenants externes</option>
            <option value="Témoins externes">Témoins externes</option>
            <option value="Entreprise cliente">Entreprise cliente</option>
            <option value="Autres entreprises présentes">Autres entreprises présentes</option>
            <option value="Sociétés partenaires">Sociétés partenaires</option>
            <option value="Organismes publics">Organismes publics</option>
            <option value="Autre">Autre</option>
        </select>
        <input class="form-control mt-2" name="tiers_autre" placeholder="Si autre">
    </div>
</div>

<h4>Catégorie d’implication</h4>
<select class="form-select" name="tiers_implication">
    <option value="Auteur présumé">Auteur présumé</option>
    <option value="Victime externe">Victime externe</option>
    <option value="Personne interpellée">Personne interpellée</option>
    <option value="Personne refusant de coopérer">Personne refusant de coopérer</option>
</select>
</div>

<div class="section-box">
<h3>B. Description factuelle</h3>
<textarea class="form-control" rows="5" name="description"></textarea>
</div>

<div class="section-box">
<h3>C. Nature du fait</h3>
<select class="form-select" name="nature_fait">
    <option>Agression verbale</option>
    <option>Agression physique</option>
    <option>Intimidation / menace</option>
    <option>Comportement inapproprié</option>
    <option>Refus d’obtempérer</option>
    <option>Dommage matériel</option>
    <option>Intrusion</option>
    <option>Autre</option>
</select>
<input class="form-control mt-2" name="nature_autre" placeholder="Si autre">
</div>

<div class="section-box">
<h3>D. Conséquences</h3>
<label>Pour le travailleur</label>
<input class="form-control" name="consequence_travailleur">
<input class="form-control mt-2" name="consequence_travailleur_autre" placeholder="Si autre">
</div>

<div class="section-box">
<h3>E. Actions immédiates</h3>
<input class="form-control" name="actions">
<input class="form-control mt-2" name="actions_autre" placeholder="Si autre">
<textarea class="form-control mt-2" rows="3" name="actions_details" placeholder="Détails"></textarea>
</div>

<div class="section-box">
<h3>F. Mesures proposées</h3>
<textarea class="form-control" rows="4" name="mesures"></textarea>
</div>

<div class="section-box">
<h3>G. Signatures</h3>
<div class="row">
    <div class="col-md-6"><label>Nom travailleur</label><input class="form-control" name="signature_nom"></div>
    <div class="col-md-6"><label>Date</label><input type="date" class="form-control" name="signature_date"></div>
</div>

<div class="row mt-3">
    <div class="col-md-6"><label>Nom responsable</label><input class="form-control" name="responsable_nom"></div>
    <div class="col-md-6"><label>Date</label><input type="date" class="form-control" name="responsable_date"></div>
</div>
</div>

<button class="btn btn-primary mt-4">Enregistrer</button>

</form>
</div>

</body>
</html>
