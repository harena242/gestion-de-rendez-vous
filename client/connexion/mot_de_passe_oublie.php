<?php
// Installation de PHPMailer (dans le dossier du projet) :
//   composer require phpmailer/phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "vendor/autoload.php";

$conn = new mysqli("localhost", "root", "", "gestion_de_rendez_vous");
if ($conn->connect_error) {
    header("Location: mot_de_passe_oublie.html?resultat=erreur_mail");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: mot_de_passe_oublie.html");
    exit;
}

$email = trim($_POST["email"] ?? "");

// --- 1) L'email existe-t-il en base ? ---
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: mot-de-passe-oublie.html?resultat=inconnu");
    exit;
}

// --- 2) Générer un code à 6 chiffres, valable 15 minutes, et le stocker ---
// Il faut ajouter 2 colonnes à la table users :
//   ALTER TABLE users ADD reset_code VARCHAR(10) NULL, ADD reset_expire DATETIME NULL;
$code = random_int(100000, 999999);
$expire = date("Y-m-d H:i:s", time() + 15 * 60);

$stmt = $conn->prepare("UPDATE users SET reset_code = ?, reset_expire = ? WHERE email = ?");
$code_texte = (string) $code;
$stmt->bind_param("sss", $code_texte, $expire, $email);
$stmt->execute();

// --- 3) Envoyer l'email avec PHPMailer (SMTP) ---
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = "smtp.gmail.com";
    $mail->SMTPAuth   = true;
    $mail->Username   = "avotsoa.tiana@gmail.com";   // à remplacer
    $mail->Password   = "mot-de-passe-application"; // mot de passe d'application Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = "UTF-8";

    $mail->setFrom("avotsoa.tiana@gmail.com", "Gestion de rendez-vous");
    $mail->addAddress($email);

    $mail->Subject = "Votre code de vérification";
    $mail->Body    = "Votre code de vérification est : $code\nIl est valable 15 minutes.";

    $mail->send();
    header("Location: mot-de-passe-oublie.html?resultat=envoye");
} catch (Exception $e) {
    header("Location: mot-de-passe-oublie.html?resultat=erreur_mail");
}
exit;
?>