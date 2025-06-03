<?php
session_start();
include 'config.php';

// Vérifie que l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Accès refusé. Seuls les administrateurs peuvent uploader des documents.";
    exit;
}

$utilisateur = $_POST['utilisateur'] ?? '';
$fichier = $_FILES['document'] ?? null;

if (!$utilisateur) {
    echo "Utilisateur non spécifié.";
    exit;
}

if (!$fichier || $fichier['error'] !== 0) {
    echo "Aucun fichier reçu ou erreur de transfert.";
    exit;
}

// Vérifie l'extension du fichier
$nom_original = basename($fichier['name']);
$extension = strtolower(pathinfo($nom_original, PATHINFO_EXTENSION));
$extensions_autorisees = ['pdf', 'docx', 'png', 'jpg', 'jpeg'];

if (!in_array($extension, $extensions_autorisees)) {
    echo "Extension non autorisée.";
    exit;
}

// Génère un nom unique
$nom_unique = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $nom_original);
$chemin = "../uploads/$nom_unique";

// Déplacement du fichier
if (move_uploaded_file($fichier['tmp_name'], $chemin)) {
    $nom_sql = $conn->real_escape_string($nom_unique);
    $utilisateur_sql = $conn->real_escape_string($utilisateur);

    $stmt = $conn->prepare("INSERT INTO documents (utilisateur, nom_fichier) VALUES (?, ?)");
    $stmt->bind_param("ss", $utilisateur_sql, $nom_sql);
    $stmt->execute();

    echo "Fichier uploadé avec succès.";
} else {
    echo "Erreur lors de l'upload.";
}
?>
