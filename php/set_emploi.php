<?php
session_start();
include 'config.php';

// Vérifie que l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["success" => false, "error" => "Accès refusé."]);
    exit;
}

// Lire les données envoyées en JSON
$data = json_decode(file_get_contents("php://input"), true);

// Récupérer et sécuriser les champs
$date = $conn->real_escape_string($data['date'] ?? '');
$heure = $conn->real_escape_string($data['heure'] ?? '');
$matiere = $conn->real_escape_string($data['matiere'] ?? '');
$enseignant = $conn->real_escape_string($data['enseignant'] ?? '');

if ($date && $heure) {
    // Vérifier que la date est bien au format YYYY-MM-DD
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        echo json_encode(["success" => false, "error" => "Format de date invalide"]);
        exit;
    }

    // Utiliser REPLACE INTO pour insérer ou mettre à jour une case unique
    $sql = "REPLACE INTO emploi_du_temps (date, heure, matiere, enseignant)
            VALUES ('$date', '$heure', '$matiere', '$enseignant')";

    if ($conn->query($sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Champs manquants"]);
}
?>
