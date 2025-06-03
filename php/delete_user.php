<?php
session_start();
include 'config.php';

// Vérifie que l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["success" => false, "error" => "Accès refusé"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$nom = $conn->real_escape_string($data['nom'] ?? '');

if ($nom) {
    $sql = "DELETE FROM utilisateurs WHERE nom = '$nom'";
    if ($conn->query($sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Nom manquant"]);
}
?>
