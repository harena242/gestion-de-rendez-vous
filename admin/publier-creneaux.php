<?php
// Ce fichier ajoute un nouveau créneau disponible dans la base

// On vérifie d'abord que c'est l'admin
require "verifier-admin.php";

$date = $_POST['date'];
$heure_debut = $_POST['heure_debut'];
$heure_fin = $_POST['heure_fin'];

// 1. L'heure de fin doit être après l'heure de début
if ($heure_fin <= $heure_debut) {
    echo json_encode(["message" => "L'heure de fin doit être après l'heure de début."]);
    exit;
}

// 2. On vérifie que ce créneau n'existe pas déjà
$requete = $pdo->prepare(
    "SELECT id FROM creneaux WHERE date_creneau = ? AND heure_debut = ?"
);
$requete->execute([$date, $heure_debut]);

if ($requete->fetch()) {
    echo json_encode(["message" => "Ce créneau existe déjà."]);
    exit;
}

// 3. On ajoute le créneau, avec le statut "disponible"
$requete = $pdo->prepare(
    "INSERT INTO creneaux (date_creneau, heure_debut, heure_fin, statut)
     VALUES (?, ?, ?, 'disponible')"
);
$requete->execute([$date, $heure_debut, $heure_fin]);

echo json_encode(["message" => "Créneau publié."]);