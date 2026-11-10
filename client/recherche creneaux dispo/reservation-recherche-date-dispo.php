<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["succes" => false, "message" => "Vous devez être connecté."]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "gestion_de_rendez_vous");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["succes" => false, "message" => "Erreur du serveur."]);
    exit;
}

$user_id = $_SESSION["user_id"];
$creneau_id = (int) ($_POST["creneau_id"] ?? 0);

$conn->begin_transaction();

try {
    // 1) Bloquer le créneau SEULEMENT s'il est encore disponible
    $stmt = $conn->prepare("UPDATE creneaux SET statut = 'reserve' WHERE id = ? AND statut = 'disponible'");
    $stmt->bind_param("i", $creneau_id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        $conn->rollback();
        echo json_encode(["succes" => false, "message" => "Ce créneau n'est plus disponible."]);
        exit;
    }

    // 2) Enregistrer le rendez-vous
    $stmt = $conn->prepare("INSERT INTO rendezvous (user_id, creneau_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $creneau_id);
    $stmt->execute();

    $conn->commit();
    echo json_encode(["succes" => true, "creneau_id" => $creneau_id]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(["succes" => false, "message" => "Erreur lors de la réservation."]);
}
?>