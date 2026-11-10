// On récupère les éléments de la page
var liste = document.getElementById("liste-rendez-vous");
var messageVide = document.getElementById("message-vide");

// Fonction qui demande les rendez-vous au PHP et les affiche
function afficherRendezVous() {
  fetch("mes-rendez-vous.php")
    .then(function (reponse) {
      return reponse.json();
    })
    .then(function (rendezVous) {
      // On vide le tableau avant de le remplir
      liste.innerHTML = "";

      // Si la liste est vide, on affiche le message
      if (rendezVous.length === 0) {
        messageVide.style.display = "block";
        return;
      }
      messageVide.style.display = "none";

      // Pour chaque rendez-vous, on ajoute une ligne dans le tableau
      for (var i = 0; i < rendezVous.length; i++) {
        var rdv = rendezVous[i];

        liste.innerHTML +=
          "<tr>" +
          "<td>" + rdv.date_creneau + "</td>" +
          "<td>" + rdv.heure_debut + "</td>" +
          "<td>" + rdv.heure_fin + "</td>" +
          "<td><button onclick='annuler(" + rdv.id + ")'>Annuler</button></td>" +
          "</tr>";
      }
    });
}

// Fonction appelée quand on clique sur "Annuler"
function annuler(idRendezVous) {
  var sur = confirm("Voulez-vous vraiment annuler ce rendez-vous ?");
  if (!sur) {
    return;
  }

  // On envoie l'id du rendez-vous au PHP
  var donnees = new FormData();
  donnees.append("id", idRendezVous);

  fetch("annuler-rendez-vous.php", {
    method: "POST",
    body: donnees
  })
    .then(function (reponse) {
      return reponse.json();
    })
    .then(function (resultat) {
      alert(resultat.message);
      // On recharge la liste pour voir le changement
      afficherRendezVous();
    });
}

// Au chargement de la page, on affiche la liste
afficherRendezVous();