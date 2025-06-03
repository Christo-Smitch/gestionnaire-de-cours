<?php
// php/login.php
session_start();
include 'config.php';

// Lire les données JSON
$data = json_decode(file_get_contents("php://input"), true);

$nom = trim($data['nom'] ?? '');
$mot_de_passe = $data['mot_de_passe'] ?? '';

if (!$nom || !$mot_de_passe) {
    echo json_encode(["success" => false, "error" => "Champs manquants"]);
    exit;
}

// Requête sécurisée pour récupérer l'utilisateur
$stmt = $conn->prepare("SELECT nom, mot_de_passe, role FROM utilisateurs WHERE nom = ?");
$stmt->bind_param("s", $nom);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Vérification du mot de passe hashé
    if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['role'] = $user['role'];

        echo json_encode([
            "success" => true,
            "nom" => $user['nom'],
            "role" => $user['role']
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "Mot de passe incorrect"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Utilisateur non trouvé"]);
}
?>
