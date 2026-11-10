<?php
// Ce fichier vérifie que la personne connectée est bien l'admin (l'entreprise).
// Les autres fichiers PHP de l'admin l'appellent au tout début avec : require "verifier-admin.php";

session_start();

// L'e-mail de l'entreprise 
$email_admin = "avo@gmail.com";

// 1. L'utilisateur doit être connecté
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

// Connexion à la base de données
$pdo = new PDO(
    'mysql:host=localhost;dbname=gestion_de_rendez_vous;charset=utf8mb4',
    'root',
    ''
);

// 2. On cherche l'e-mail de l'utilisateur connecté
$requete = $pdo->prepare("SELECT email FROM users WHERE id = ?");
$requete->execute([$_SESSION['user_id']]);
$utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

// 3. Si ce n'est pas l'e-mail de l'admin, on arrête tout
if (!$utilisateur || $utilisateur['email'] != $email_admin) {
    http_response_code(403);
    exit;
}

// Si on arrive ici, c'est bien l'admin.
// La variable $pdo reste utilisable dans les fichiers qui appellent celui-ci.