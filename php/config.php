
<?php
// php/config.php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'gestion_cours';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
  die("Connexion à la base de données échouée: " . $conn->connect_error);
}
?>
