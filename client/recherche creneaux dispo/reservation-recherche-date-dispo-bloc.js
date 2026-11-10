// Les boutons "Réserver" sont créés par recherche-date-dispo.js,
// donc on écoute les clics sur toute la zone des résultats.
document.getElementById("resultats").addEventListener("click", function (event) {
    if (!event.target.classList.contains("btn-reserver")) return;

    const bouton = event.target;
    const creneauId = bouton.dataset.id;
    bouton.disabled = true; // évite le double clic

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "reservation-recherche-date-dispo.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        const reponse = JSON.parse(xhr.responseText);

        if (reponse.succes) {
            // Bloquer le créneau dans l'affichage
            bouton.textContent = "Réservé";
            document.getElementById("ligne-" + creneauId).style.opacity = "0.5";
            document.getElementById("message").textContent = "Rendez-vous réservé avec succès.";
        } else {
            bouton.disabled = false;
            document.getElementById("message").textContent = reponse.message;
        }
    };

    xhr.onerror = function () {
        bouton.disabled = false;
        document.getElementById("message").textContent = "Erreur de connexion au serveur.";
    };

    xhr.send("creneau_id=" + encodeURIComponent(creneauId));
});