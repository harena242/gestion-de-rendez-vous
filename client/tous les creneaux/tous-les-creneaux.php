<?php
// Ce fichier renvoie tous les créneaux, réservés ou non

header("Content-Type: application/json; charset=UTF-8");

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "gestion_de_rendez_vous");
if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

// On prend tous les créneaux, rangés par date puis par heure
$stmt = $conn->prepare(
    "SELECT id, date_creneau, heure_debut, heure_fin, statut
     FROM creneaux
     ORDER BY date_creneau, heure_debut"
);
$stmt->execute();
$result = $stmt->get_result();

// On renvoie le résultat au JavaScript
echo json_encode($result->fetch_all(MYSQLI_ASSOC));