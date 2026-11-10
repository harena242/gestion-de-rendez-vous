// Fonction appelée quand on clique sur "Publier"

function publier() {
  // 1. On récupère ce que l'admin a écrit
  var date = document.getElementById("date").value;
  var heureDebut = document.getElementById("heure-debut").value;
  var heureFin = document.getElementById("heure-fin").value;

  // 2. On vérifie que tout est rempli
  if (date === "" || heureDebut === "" || heureFin === "") {
    alert("Veuillez remplir tous les champs.");
    return;
  }

  // 3. On envoie les données au PHP
  var donnees = new FormData();
  donnees.append("date", date);
  donnees.append("heure_debut", heureDebut);
  donnees.append("heure_fin", heureFin);

  fetch("publier-creneaux.php", {
    method: "POST",
    body: donnees
  })
    .then(function (reponse) {
      // Si ce n'est pas l'admin, on renvoie vers la connexion
      if (reponse.status === 403) {
        window.location = "../connexion/login.html";
      }
      return reponse.json();
    })
    .then(function (resultat) {
      alert(resultat.message);
    });
}