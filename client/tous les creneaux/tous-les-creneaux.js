// On récupère les éléments de la page
var liste = document.getElementById("liste-creneaux");
var messageVide = document.getElementById("message-vide");

// Fonction qui demande les créneaux au PHP et les affiche
function afficherCreneaux() {
  fetch("tous-les-creneaux.php")
    .then(function (reponse) {
      return reponse.json();
    })
    .then(function (creneaux) {
      // On vide le tableau avant de le remplir
      liste.innerHTML = "";

      // S'il n'y a aucun créneau, on affiche le message
      if (creneaux.length === 0) {
        messageVide.style.display = "block";
        return;
      }
      messageVide.style.display = "none";

      // Pour chaque créneau, on ajoute une ligne dans le tableau
      for (var i = 0; i < creneaux.length; i++) {
        var creneau = creneaux[i];

        // Si le créneau est disponible : bouton Réserver, sinon : "Réservé"
        var action = "Réservé";
        if (creneau.statut === "disponible") {
          action = "<button onclick='reserver(" + creneau.id + ")'>Réserver</button>";
        }

        liste.innerHTML +=
          "<tr>" +
          "<td>" + creneau.date_creneau + "</td>" +
          "<td>" + creneau.heure_debut + "</td>" +
          "<td>" + creneau.heure_fin + "</td>" +
          "<td>" + action + "</td>" +
          "</tr>";
      }
    });
}

// Fonction appelée quand on clique sur "Réserver"
function reserver(idCreneau) {
  // On envoie l'id du créneau au PHP de réservation
  var donnees = new FormData();
  donnees.append("creneau_id", idCreneau);

  fetch("../recherche%20creneaux%20dispo/reservation-recherche-date-dispo.php", {
//   fetch("reservation-recherche-date-dispo.php", {
    method: "POST",
    body: donnees
  })
    .then(function (reponse) {
      return reponse.json();
    })
    .then(function (resultat) {
      if (resultat.succes) {
        alert("Rendez-vous réservé !");
      } else {
        alert(resultat.message);
      }
      // On recharge la liste pour voir le changement
      afficherCreneaux();
    });
}

// Au chargement de la page, on affiche tous les créneaux
afficherCreneaux();