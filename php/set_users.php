<?php
// php/set_users.php
session_start();
include 'config.php';

// Affiche les erreurs pour le debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Vérifie que l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["success" => false, "error" => "Accès refusé"]);
    exit;
}

// Requête pour récupérer nom et rôle
$sql = "SELECT nom, role FROM utilisateurs";
$result = $conn->query($sql);

$utilisateurs = [];
while ($row = $result->fetch_assoc()) {
    $utilisateurs[] = $row;
}

header('Content-Type: application/json');
echo json_encode($utilisateurs);
?>
