<?php
require_once 'auth.php';
require_login();

function join_or_null($arr) {
    return isset($arr) && is_array($arr) ? implode(', ', $arr) : null;
}

$lieu = join_or_null($_POST['lieu']) ?? '';
if (!empty($_POST['lieu_autre'])) $lieu .= ($lieu ? ', ' : '') . $_POST['lieu_autre'];

$travailleurs = [];
if (!empty($_POST['travailleur1_nom'])) {
    $travailleurs[] = $_POST['travailleur1_nom'] . ' (' . ($_POST['travailleur1_fonction'] ?? '') . ')';
}
if (!empty($_POST['travailleur2_nom'])) {
    $travailleurs[] = $_POST['travailleur2_nom'] . ' (' . ($_POST['travailleur2_fonction'] ?? '') . ')';
}
$travailleurs_str = implode(', ', $travailleurs);

$tiers_parts = [];
if (!empty($_POST['tiers_nom'])) $tiers_parts[] = "Nom: " . $_POST['tiers_nom'];
if (!empty($_POST['tiers_org'])) $tiers_parts[] = "Organisation: " . $_POST['tiers_org'];
if (!empty($_POST['tiers_type'])) $tiers_parts[] = "Type: " . $_POST['tiers_type'];
if (!empty($_POST['tiers_autre'])) $tiers_parts[] = "Type: " . $_POST['tiers_autre'];
$tiers = implode(' | ', $tiers_parts);

$nature = join_or_null($_POST['nature']) ?? '';
if (!empty($_POST['nature_autre'])) $nature .= ($nature ? ', ' : '') . $_POST['nature_autre'];

$cons_trav = join_or_null($_POST['cons_trav']) ?? '';
if (!empty($_POST['cons_trav_autre'])) $cons_trav .= ($cons_trav ? ', ' : '') . $_POST['cons_trav_autre'];

$cons_ent = join_or_null($_POST['cons_ent']) ?? '';
if (!empty($_POST['cons_ent_autre'])) $cons_ent .= ($cons_ent ? ', ' : '') . $_POST['cons_ent_autre'];

$actions = join_or_null($_POST['actions']) ?? '';
if (!empty($_POST['actions_autre'])) $actions .= ($actions ? ', ' : '') . $_POST['actions_autre'];
if (!empty($_POST['actions_details'])) $actions .= " | Détails: " . $_POST['actions_details'];

$stmt = $pdo->prepare("
    INSERT INTO fiches (
        date_incident, heure_incident, lieu, travailleurs, tiers, description,
        nature, consequences_travailleur, consequences_entreprise, actions,
        mesures, sign_trav_nom, sign_trav_date, sign_resp_nom, sign_resp_date
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
");

$stmt->execute([
    $_POST['date_incident'] ?? null,
    $_POST['heure_incident'] ?? null,
    $lieu,
    $travailleurs_str,
    $tiers,
    $_POST['description'] ?? null,
    $nature,
    $cons_trav,
    $cons_ent,
    $actions,
    $_POST['mesures'] ?? null,
    $_POST['sign_trav_nom'] ?? null,
    $_POST['sign_trav_date'] ?? null,
    $_POST['sign_resp_nom'] ?? null,
    $_POST['sign_resp_date'] ?? null
]);

header("Location: fiches_liste.php");
exit;
