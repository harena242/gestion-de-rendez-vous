const form = document.getElementById("form-connexion");
const messageErreur = document.getElementById("message-erreur");

// 1) Afficher l'erreur renvoyée par connexion.php (dans l'URL : ?erreur=...)
function afficherErreurDepuisUrl() {
    const params = new URLSearchParams(window.location.search);
    const erreur = params.get("erreur");

    if (erreur === "utilisateur") {
        messageErreur.textContent = "Ce nom d'utilisateur n'existe pas.";
    } else if (erreur === "motdepasse") {
        messageErreur.textContent = "Mot de passe incorrect.";
    } else if (erreur === "serveur") {
        messageErreur.textContent = "Erreur du serveur. Réessayez plus tard.";
    }
}

// 2) Vérifier que les champs ne sont pas vides avant l'envoi
form.addEventListener("submit", function (event) {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value;

    if (username === "" || password === "") {
        event.preventDefault(); // on bloque l'envoi
        messageErreur.textContent = "Veuillez remplir tous les champs.";
    }
});

afficherErreurDepuisUrl();