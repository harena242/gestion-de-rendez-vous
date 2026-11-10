<?php
// Ce fichier renvoie les rendez-vous, filtrés par nom d'utilisateur et/ou par date

// On vérifie d'abord que c'est l'admin (le fichier est dans le dossier du dessus)
require "../verifier-admin.php";

// Ce que l'admin a écrit (vide = pas de filtre)
// Le signe % veut dire "n'importe quoi", donc un champ vide accepte tout
$nom = "%" . $_GET['nom'] . "%";
$date = "%" . $_GET['date'] . "%";

$requete = $pdo->prepare(
    "SELECT users.username, creneaux.date_creneau, creneaux.heure_debut, creneaux.heure_fin
     FROM rendezvous
     JOIN users ON users.id = rendezvous.user_id
     JOIN creneaux ON creneaux.id = rendezvous.creneau_id
     WHERE users.username LIKE ? AND creneaux.date_creneau LIKE ?
     ORDER BY creneaux.date_creneau, creneaux.heure_debut"
);
$requete->execute([$nom, $date]);

$rendez_vous = $requete->fetchAll(PDO::FETCH_ASSOC);

// On renvoie le résultat au JavaScript
echo json_encode($rendez_vous);