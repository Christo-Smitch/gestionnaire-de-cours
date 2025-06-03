<?php
session_start();
include 'config.php';

// Vérification de session et rôle
$role = $_SESSION['role'] ?? 'eleve'; // valeur par défaut : eleve
$isAdmin = ($role === 'admin');

$jours_fr = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
$heures = ['09:00-12:00', '12:00-14:00', '14:00-17:00'];

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

$today = new DateTime();
$weekday = (int) $today->format('w'); // 0 (dim) à 6 (samedi)
$daysToMonday = ($weekday === 0) ? -6 : (1 - $weekday);

// lundi de la semaine actuelle
$lundi_semaine_actuelle = (clone $today)->modify("$daysToMonday days");

// lundi de la semaine demandée (offset)
$lundi = (clone $lundi_semaine_actuelle)->modify(($offset * 7) . " days");

// Génération des jours de la semaine
$jours_semaines = [];
for ($i = 0; $i < 7; $i++) {
    $date = (clone $lundi)->modify("+$i days");
    $jours_semaines[] = [
        'nom' => $jours_fr[(int)$date->format('w')],
        'date_sql' => $date->format('Y-m-d'),
        'date_affichee' => $date->format('d/m/Y')
    ];
}

// Initialiser planning vide
$planning = [];
foreach ($jours_semaines as $jour) {
    foreach ($heures as $heure) {
        $planning[$jour['date_sql']][$heure] = ['matiere' => '', 'enseignant' => ''];
    }
}

// Charger depuis la base de données
$sql = "SELECT date, heure, matiere, enseignant FROM emploi_du_temps";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $date = $row['date'];
    $heure = $row['heure'];
    if (isset($planning[$date][$heure])) {
        $planning[$date][$heure] = [
            'matiere' => $row['matiere'],
            'enseignant' => $row['enseignant']
        ];
    }
}

// Affichage HTML
echo "<div class='planning-wrapper'>";
echo "<div class='planning-nav'>
        <button onclick=\"changerSemaine(-1)\">&lt; Semaine précédente</button>
        <button onclick=\"changerSemaine(1)\">Semaine suivante &gt;</button>
      </div>";

echo "<table id='planning-table'>";
echo "<tr><th>Heure</th>";
foreach ($jours_semaines as $jour) {
    echo "<th><div style='display: flex; flex-direction: column; align-items: center;'>
              <span><strong>{$jour['nom']}</strong></span>
              <span style='font-size: 0.85rem; color: #ccc;'>{$jour['date_affichee']}</span>
          </div></th>";
}
echo "</tr>";

foreach ($heures as $heure) {
    echo "<tr><td><strong>$heure</strong></td>";
    foreach ($jours_semaines as $jour) {
        $date = $jour['date_sql'];
        $data = $planning[$date][$heure];
        $matiere = htmlspecialchars($data['matiere']);
        $enseignant = htmlspecialchars($data['enseignant']);
        $classe = ($matiere || $enseignant) ? "rempli" : "";

        echo "<td class='planning-cell $classe' data-date='$date' data-heure='$heure'>";
        echo "<div class='contenu-cours'>";
        echo "<div class='matiere'>$matiere</div>";
        echo "<div class='enseignant'>$enseignant</div>";

        // Affiche ✏️ seulement si admin
        if ($isAdmin) {
            echo "<button class='modifier-cellule admin-visible' title='Modifier'>✏️</button>";
        }

        echo "</div></td>";
    }
    echo "</tr>";
}
echo "</table></div>";
?>
