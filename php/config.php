
<?php
// php/config.php
$host = 'mysql.railway.internal';
$user = 'root';
$password = 'JepbILXFLzxQTKdYcCxlNQoQuHUSVdAI';
$database = 'railway';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
  die("Connexion à la base de données échouée: " . $conn->connect_error);
}
?>
