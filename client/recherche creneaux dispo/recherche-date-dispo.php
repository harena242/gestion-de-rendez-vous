<?php
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "gestion_de_rendez_vous");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([]);
    exit;
}

$date = $_GET["date"] ?? "";

// Seulement les créneaux de cette date qui ne sont pas encore pris
$stmt = $conn->prepare(
    "SELECT id, date_creneau, heure_debut, heure_fin
     FROM creneaux
     WHERE date_creneau = ? AND statut = 'disponible'
     ORDER BY heure_debut"
);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode($result->fetch_all(MYSQLI_ASSOC));
?>