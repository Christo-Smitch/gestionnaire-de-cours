<?php
session_start();
include 'config.php';

// Vérifie que l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["success" => false, "error" => "Accès refusé"]);
    exit;
}

$sql = "SELECT nom, mot_de_passe, role FROM utilisateurs";
$result = $conn->query($sql);

$utilisateurs = [];
while ($row = $result->fetch_assoc()) {
    $utilisateurs[] = $row;
}

header('Content-Type: application/json');
echo json_encode($utilisateurs);
?>
