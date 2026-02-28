<?php
require_once 'auth.php';
require 'vendor/autoload.php';

use Dompdf\Dompdf;

global $pdo;

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    die("Accès refusé.");
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Vérification ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Fiche invalide.");
}

$fiche_id = (int) $_GET['id'];

// Récupération de la fiche
$stmt = $pdo->prepare("
    SELECT f.*, u.email 
    FROM fiches_tiers f
    JOIN users u ON f.user_id = u.id
    WHERE f.id = ?
");
$stmt->execute([$fiche_id]);
$fiche = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fiche) {
    die("Cette fiche n'existe pas.");
}

// Sécurité : un travailleur ne peut voir que ses fiches
if ($role !== 'admin' && $fiche['user_id'] != $user_id) {
    die("Accès refusé.");
}

// STYLE PREMIUM POUR LE PDF
$html = "
<style>
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
    color: #2A2A2A;
}

h1 {
    font-size: 22px;
    color: #0057A8;
    text-align: center;
    margin-bottom: 20px;
}

.section {
    background: #E8F1FB;
    padding: 10px 12px;
    border-radius: 8px;
    margin-top: 18px;
    margin-bottom: 10px;
    font-size: 15px;
    font-weight: bold;
    color: #0057A8;
}

.block {
    padding: 8px 12px;
    border: 1px solid #DDE3EA;
    border-radius: 8px;
    margin-bottom: 8px;
}

.label {
    font-weight: bold;
    color: #6C757D;
}

hr {
    border: none;
    border-top: 1px solid #DDE3EA;
    margin: 20px 0;
}
</style>

<h1>Fiche de renseignement de fait de tiers";

// Numéro visible uniquement pour admin
if ($role === 'admin') {
    $html .= " <span style='color:#777;'>#{$fiche['id']}</span>";
}

$html .= "</h1>

<div class='section'>1. Complété par</div>

<div class='block'><span class='label'>Nom :</span> {$fiche['nom_complet']}</div>
<div class='block'><span class='label'>Fonction :</span> {$fiche['fonction']}</div>
<div class='block'><span class='label'>Période couverte :</span> {$fiche['periode_couverte']}</div>

<div class='section'>A. Informations générales</div>

<div class='block'><span class='label'>Date :</span> {$fiche['date_incident']}</div>
<div class='block'><span class='label'>Heure :</span> {$fiche['heure_incident']}</div>

<div class='block'>
    <span class='label'>Lieu :</span> {$fiche['lieu']}
    " . ($fiche['lieu'] === 'Autre' ? "({$fiche['lieu_autre']})" : "") . "
</div>

<div class='section'>Travailleur concerné</div>

<div class='block'><span class='label'>Nom :</span> {$fiche['travailleur_nom']}</div>
<div class='block'><span class='label'>Prénom :</span> {$fiche['travailleur_prenom']}</div>
<div class='block'><span class='label'>Fonction :</span> {$fiche['travailleur_fonction']}</div>

<div class='section'>Tiers impliqué</div>

<div class='block'><span class='label'>Nom :</span> {$fiche['tiers_nom']}</div>
<div class='block'><span class='label'>Organisation :</span> {$fiche['tiers_organisation']}</div>

<div class='block'>
    <span class='label'>Type :</span> {$fiche['tiers_type']}
    " . ($fiche['tiers_type'] === 'Autre' ? "({$fiche['tiers_autre']})" : "") . "
</div>

<div class='block'><span class='label'>Catégorie d’implication :</span> {$fiche['tiers_implication']}</div>

<div class='section'>B. Description factuelle</div>
<div class='block'>" . nl2br($fiche['description']) . "</div>

<div class='section'>C. Nature du fait</div>
<div class='block'>
    <span class='label'>Nature :</span> {$fiche['nature_fait']}
    " . ($fiche['nature_fait'] === 'Autre' ? "({$fiche['nature_autre']})" : "") . "
</div>

<div class='section'>D. Conséquences constatées</div>
<div class='block'>
    <span class='label'>Pour le travailleur :</span> {$fiche['consequence_travailleur']}
    " . (!empty($fiche['consequence_travailleur_autre']) ? "({$fiche['consequence_travailleur_autre']})" : "") . "
</div>

<div class='section'>E. Actions immédiates</div>
<div class='block'>
    <span class='label'>Actions :</span> {$fiche['actions']}
    " . (!empty($fiche['actions_autre']) ? "({$fiche['actions_autre']})" : "") . "
</div>

<div class='block'>
    <span class='label'>Détails :</span><br>
    " . nl2br($fiche['actions_details']) . "
</div>

<div class='section'>F. Mesures proposées</div>
<div class='block'>" . nl2br($fiche['mesures']) . "</div>

<div class='section'>G. Signatures</div>

<div class='block'>
    <span class='label'>Travailleur :</span> {$fiche['signature_nom']}<br>
    <span class='label'>Date :</span> {$fiche['signature_date']}
</div>

<div class='block'>
    <span class='label'>Responsable :</span> {$fiche['responsable_nom']}<br>
    <span class='label'>Date :</span> {$fiche['responsable_date']}
</div>
";

// Génération du PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("fiche_tiers_$fiche_id.pdf");
