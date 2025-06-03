<?php
// php/get_docs.php
session_start();
include 'config.php';
$role = $_SESSION['role'] ?? 'eleve';

// Récupération du nom d'utilisateur depuis la session
$utilisateur = $conn->real_escape_string($_SESSION['nom'] ?? '');

if (!$utilisateur) {
  exit("Utilisateur non connecté.");
}

$sql = "SELECT nom_fichier FROM documents WHERE utilisateur = '$utilisateur'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  echo '<p><a href="../uploads/' . htmlspecialchars($row['nom_fichier']) . '" target="_blank">' . htmlspecialchars($row['nom_fichier']) . '</a></p>';
}
?>
