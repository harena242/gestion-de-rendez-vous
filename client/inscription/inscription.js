// Vérifie que l'adresse email est correcte (ex: nom@domaine.com)
function validerEmail(email) {
    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        return "L'adresse email n'est pas correcte.";
    }
    return ""; // vide = valide
}

// Vérifie le mot de passe : 8 caractères minimum, une majuscule,
// un chiffre et un caractère spécial
function validerMotDePasse(motDePasse) {
    if (motDePasse.length < 8) {
        return "Le mot de passe doit contenir au moins 8 caractères.";
    }
    if (!/[A-Z]/.test(motDePasse)) {
        return "Le mot de passe doit contenir au moins une lettre majuscule.";
    }
    if (!/[0-9]/.test(motDePasse)) {
        return "Le mot de passe doit contenir au moins un chiffre.";
    }
    if (!/[^A-Za-z0-9]/.test(motDePasse)) {
        return "Le mot de passe doit contenir au moins un caractère spécial.";
    }
    return ""; // vide = valide
}

// Validation à l'envoi du formulaire
document.getElementById("formInscription").addEventListener("submit", function (event) {
    const email = document.getElementById("email").value.trim();
    const motDePasse = document.getElementById("password").value;

    let erreur = validerEmail(email);
    if (erreur === "") {
        erreur = validerMotDePasse(motDePasse);
    }

    if (erreur !== "") {
        event.preventDefault(); // bloque l'envoi vers inscription.php
        document.getElementById("message").textContent = erreur;
    }
});

// Affiche les erreurs renvoyées par inscription.php (ex: inscription.html?erreur=username)
const params = new URLSearchParams(window.location.search);
const erreurServeur = params.get("erreur");

if (erreurServeur === "username") {
    document.getElementById("message").textContent =
        "Ce nom d'utilisateur est déjà utilisé, veuillez en choisir un autre.";
} else if (erreurServeur === "champs") {
    document.getElementById("message").textContent = "Veuillez remplir tous les champs.";
} else if (erreurServeur === "email") {
    document.getElementById("message").textContent = "L'adresse email n'est pas correcte.";
} else if (erreurServeur === "motdepasse") {
    document.getElementById("message").textContent = "Mot de passe invalide.";
} else if (erreurServeur === "serveur") {
    document.getElementById("message").textContent = "Erreur du serveur, réessayez plus tard.";
}