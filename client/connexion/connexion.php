<?php
session_start();

// --- Connexion à la base de données (à adapter) ---
$conn = new mysqli("localhost", "root", "", "gestion_de_rendez_vous");
if ($conn->connect_error) {
    header("Location: connexion.html?erreur=serveur");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: connexion.html");
    exit;
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

// --- 1) Le nom d'utilisateur existe-t-il ? ---
// $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt = $conn->prepare("SELECT id, password, email FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: connexion.html?erreur=utilisateur");
    exit;
}

$user = $result->fetch_assoc();

// --- 2) Le mot de passe correspond-il ? ---
// (suppose que le mot de passe a été enregistré avec password_hash() à l'inscription)
if (!password_verify($password, $user["password"])) {
    header("Location: connexion.html?erreur=motdepasse");
    exit;
}

// --- 3) Tout est bon : on connecte l'utilisateur ---
$_SESSION["user_id"] = $user["id"];
$_SESSION["username"] = $username;

// header("Location: ../recherche%20creneaux%20dispo/recherche_creneaux_dispo.html");
// Si c'est l'e-mail de l'entreprise, on va vers l'admin
if ($user["email"] == "avo@gmail.com") {
    header("Location: ../../admin/accueil-admin.html");
    exit;
}

// Sinon, c'est un client
header("Location: ../accueil/accueil-client.html");
exit;
exit;
?>