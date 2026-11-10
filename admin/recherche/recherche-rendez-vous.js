// On récupère les éléments de la page
var liste = document.getElementById("liste-rendez-vous");
var messageVide = document.getElementById("message-vide");

// Fonction qui demande les rendez-vous au PHP et les affiche
function rechercher() {
  // 1. On récupère ce que l'admin a écrit (peut être vide)
  var nom = document.getElementById("nom").value;
  var date = document.getElementById("date").value;

  // 2. On envoie la recherche au PHP
  var adresse = "recherche-rendez-vous.php?nom=" + encodeURIComponent(nom) + "&date=" + date;

  fetch(adresse)
    .then(function (reponse) {
      // Si ce n'est pas l'admin, on prévient
      if (reponse.status === 403) {
        alert("Accès refusé : connectez-vous avec le compte de l'entreprise.");
      }
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
          "<td>" + rdv.username + "</td>" +
          "<td>" + rdv.date_creneau + "</td>" +
          "<td>" + rdv.heure_debut + "</td>" +
          "<td>" + rdv.heure_fin + "</td>" +
          "</tr>";
      }
    });
}

// Fonction appelée quand on clique sur "Tout afficher"
function toutAfficher() {
  document.getElementById("nom").value = "";
  document.getElementById("date").value = "";
  rechercher();
}

// Au chargement de la page, on affiche tous les rendez-vous
rechercher();