<?php
// Forcer l'utilisation de la session si le cookie est présent
if (isset($_COOKIE['PHPSESSID'])) {
    session_id($_COOKIE['PHPSESSID']);
}

session_start();
include 'config.php';

header('Content-Type: application/json');

// 🔒 Vérifie que seul un admin peut accéder à ce script
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["success" => false, "error" => "Accès refusé"]);
    exit;
}

// Lecture des données JSON
$data = json_decode(file_get_contents("php://input"), true);

$nom = trim($data['nom'] ?? '');
$mot_de_passe = $data['mot_de_passe'] ?? '';
$role = $data['role'] ?? '';

if ($nom && $mot_de_passe && in_array($role, ['admin', 'prof', 'eleve'])) {
    // 🔐 Hash sécurisé
    $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Insertion sécurisée
    $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, mot_de_passe, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nom, $hash, $role);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Champs invalides"]);
}
