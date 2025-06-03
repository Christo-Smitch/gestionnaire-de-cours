<?php
session_start();
include 'config.php';

$role = $_SESSION['role'] ?? 'eleve';

$sql = "SELECT id, auteur, message, date_envoi FROM messages ORDER BY date_envoi DESC LIMIT 10";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $auteur = htmlspecialchars($row['auteur']);
  $message = nl2br(htmlspecialchars($row['message']));
  $date = date('d/m/Y H:i', strtotime($row['date_envoi']));
  $id = intval($row['id']);

  echo "<div class='message-box'>";
  echo "<div class='message-meta'>{$auteur}<br><small>{$date}</small></div>";
  echo "<div class='message-content'>{$message}</div>";

  if ($role === "admin") {
    echo "<button class='delete-message' data-id='{$id}' title='Supprimer'>🗑</button>";
  }

  echo "</div>";
}
?>
