const formRecherche = document.getElementById("form-recherche");
const divResultats = document.getElementById("resultats");
const message = document.getElementById("message");

formRecherche.addEventListener("submit", function (event) {
    event.preventDefault(); // pas de rafraîchissement de la page

    const date = document.getElementById("date").value;
    if (date === "") {
        message.textContent = "Veuillez choisir une date.";
        return;
    }
    message.textContent = "";

    // Requête AJAX (XMLHttpRequest)
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "recherche-date-dispo.php?date=" + encodeURIComponent(date), true);

    xhr.onload = function () {
        if (xhr.status !== 200) {
            message.textContent = "Erreur lors de la recherche.";
            return;
        }
        const creneaux = JSON.parse(xhr.responseText);
        afficherCreneaux(creneaux);
    };

    xhr.onerror = function () {
        message.textContent = "Erreur de connexion au serveur.";
    };

    xhr.send();
});

function afficherCreneaux(creneaux) {
    if (creneaux.length === 0) {
        divResultats.innerHTML = "<p>Aucun créneau disponible pour cette date.</p>";
        return;
    }

    let html = "<table border='1'>" +
        "<tr><th>Date</th><th>Heure de début</th><th>Heure de fin</th><th>Réservation</th></tr>";

    creneaux.forEach(function (c) {
        html += "<tr id='ligne-" + c.id + "'>" +
            "<td>" + c.date_creneau + "</td>" +
            "<td>" + c.heure_debut + "</td>" +
            "<td>" + c.heure_fin + "</td>" +
            "<td><button class='btn-reserver' data-id='" + c.id + "'>Réserver</button></td>" +
            "</tr>";
    });

    html += "</table>";
    divResultats.innerHTML = html;
}