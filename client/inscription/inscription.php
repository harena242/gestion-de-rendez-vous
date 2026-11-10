<?php
// Connexion à la base de données (adapte les valeurs à ta configuration)
$conn = new mysqli("localhost", "root", "", "gestion_de_rendez_vous");

if ($conn->connect_error) {
    header("Location: inscription.html?erreur=serveur");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: inscription.html");
    exit;
}

$username = trim($_POST["username"] ?? "");
$email    = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

// Champs obligatoires
if ($username === "" || $email === "" || $password === "") {
    header("Location: inscription.html?erreur=champs");
    exit;
}

// Revalidation de l'email côté serveur
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: inscription.html?erreur=email");
    exit;
}

// Revalidation du mot de passe côté serveur
if (strlen($password) < 8
    || !preg_match('/[A-Z]/', $password)
    || !preg_match('/[0-9]/', $password)
    || !preg_match('/[^A-Za-z0-9]/', $password)) {
    header("Location: inscription.html?erreur=motdepasse");
    exit;
}

// 1) Le nom d'utilisateur est-il déjà utilisé ?
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    $conn->close();
    header("Location: inscription.html?erreur=username"); // à changer
    exit;
}
$stmt->close();

// 2) L'adresse email est-elle déjà utilisée ?
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    $conn->close();
    header("Location: login.html"); // compte existant -> connexion
    exit;
}
$stmt->close();

// 3) Ni l'un ni l'autre n'existe : on insère
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $hash);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: ../recherche%20creneaux%20dispo/recherche_creneaux_dispo.html");
exit;