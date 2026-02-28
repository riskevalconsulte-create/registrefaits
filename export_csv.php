<?php
require_once 'auth.php';
require_role('admin');
global $pdo;

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="fiches.csv"');

$output = fopen('php://output', 'w');

// En-têtes
fputcsv($output, [
    'ID', 'Date incident', 'Heure', 'Lieu', 'Travailleurs', 'Tiers',
    'Description', 'Nature', 'Conséquences travailleur', 'Conséquences entreprise',
    'Actions', 'Mesures', 'Sign. travailleur', 'Date sign. travailleur',
    'Sign. responsable', 'Date sign. responsable', 'Créée le'
]);

$stmt = $pdo->query("SELECT * FROM fiches ORDER BY created_at DESC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['id'],
        $row['date_incident'],
        $row['heure_incident'],
        $row['lieu'],
        $row['travailleurs'],
        $row['tiers'],
        $row['description'],
        $row['nature'],
        $row['consequences_travailleur'],
        $row['consequences_entreprise'],
        $row['actions'],
        $row['mesures'],
        $row['sign_trav_nom'],
        $row['sign_trav_date'],
        $row['sign_resp_nom'],
        $row['sign_resp_date'],
        $row['created_at']
    ]);
}

fclose($output);
exit;
