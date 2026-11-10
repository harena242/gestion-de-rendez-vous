<?php
// Ce fichier annule un rendez-vous et rend le créneau disponible

session_start();

// L'utilisateur doit être connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["message" => "Vous devez être connecté."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$id_rendez_vous = $_POST['id'];

// Connexion à la base de données
$pdo = new PDO(
    'mysql:host=localhost;dbname=gestion_de_rendez_vous;charset=utf8mb4',
    'root',
    ''
);

// 1. On cherche le rendez-vous (il doit appartenir à cet utilisateur)
$requete = $pdo->prepare(
    "SELECT rendezvous.creneau_id, creneaux.date_creneau
     FROM rendezvous
     JOIN creneaux ON creneaux.id = rendezvous.creneau_id
     WHERE rendezvous.id = ? AND rendezvous.user_id = ?"
);
$requete->execute([$id_rendez_vous, $user_id]);
$rdv = $requete->fetch(PDO::FETCH_ASSOC);

if (!$rdv) {
    echo json_encode(["message" => "Rendez-vous introuvable."]);
    exit;
}

// 2. Règle : on peut annuler au plus tard 2 jours avant la date
$date_rdv = strtotime($rdv['date_creneau']);
$date_limite = strtotime("-2 days", $date_rdv);

if (time() > $date_limite) {
    echo json_encode(["message" => "Annulation impossible : il faut annuler au moins 2 jours avant."]);
    exit;
}

// 3. On supprime le rendez-vous
$requete = $pdo->prepare("DELETE FROM rendezvous WHERE id = ?");
$requete->execute([$id_rendez_vous]);

// 4. On rend le créneau disponible à nouveau
$requete = $pdo->prepare("UPDATE creneaux SET statut = 'disponible' WHERE id = ?");
$requete->execute([$rdv['creneau_id']]);

echo json_encode(["message" => "Rendez-vous annulé."]);