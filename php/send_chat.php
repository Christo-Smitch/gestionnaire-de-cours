<?php
// php/send_chat.php
session_start();
include 'config.php';

// Vérifie que l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["success" => false, "error" => "Accès refusé. Seuls les administrateurs peuvent envoyer des messages."]);
    exit;
}

// Vérifie que le nom de l'auteur est cohérent avec la session
$data = json_decode(file_get_contents("php://input"), true);

$auteur = $conn->real_escape_string($data['auteur'] ?? '');
$message = $conn->real_escape_string($data['message'] ?? '');

if (!$auteur || !$message) {
    echo json_encode(["success" => false, "error" => "Champs manquants"]);
    exit;
}

// Vérifie que le nom correspond bien à la session
if ($_SESSION['nom'] !== $auteur) {
    echo json_encode(["success" => false, "error" => "Auteur invalide"]);
    exit;
}

$sql = "INSERT INTO messages (auteur, message, date_envoi) VALUES ('$auteur', '$message', NOW())";

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}
?>
