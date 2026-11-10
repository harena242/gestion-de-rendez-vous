<?php
// Ce fichier renvoie la liste des rendez-vous de l'utilisateur connecté

session_start();

// L'utilisateur doit être connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Connexion à la base de données
$pdo = new PDO(
    'mysql:host=localhost;dbname=gestion_de_rendez_vous;charset=utf8mb4',
    'root',
    ''
);

// On prend les rendez-vous de cet utilisateur, avec la date et les heures du créneau
$requete = $pdo->prepare(
    "SELECT rendezvous.id, creneaux.date_creneau, creneaux.heure_debut, creneaux.heure_fin
     FROM rendezvous
     JOIN creneaux ON creneaux.id = rendezvous.creneau_id
     WHERE rendezvous.user_id = ?
     ORDER BY creneaux.date_creneau, creneaux.heure_debut"
);
$requete->execute([$user_id]);

$rendez_vous = $requete->fetchAll(PDO::FETCH_ASSOC);

// On renvoie le résultat au JavaScript
echo json_encode($rendez_vous);