# Gestion de rendez-vous

Application web de gestion de rendez-vous pour une entreprise. Les clients créent un compte, recherchent les créneaux disponibles et réservent en ligne. L'entreprise (admin) publie ses horaires et suit les rendez-vous reçus.

## Fonctionnalités

### Côté client

- Inscription et connexion
- Recherche des créneaux disponibles par date
- Affichage de tous les créneaux disponibles
- Réservation d'un rendez-vous
- Liste de mes rendez-vous
- Annulation d'un rendez-vous (au plus tard 2 jours avant la date)

### Côté entreprise (admin)

- Publication des créneaux (date, heure de début, heure de fin)
- Liste de tous les rendez-vous, avec le nom du client
- Recherche des rendez-vous par nom d'utilisateur ou par date

### Règles automatiques

- Un rendez-vous est confirmé automatiquement dès que le client réserve
- Le créneau réservé est bloqué automatiquement
- Un créneau annulé redevient disponible

## Technologies utilisées

- HTML, CSS, JavaScript (AJAX pour la recherche sans rechargement de page)
- PHP (mysqli, sessions)
- MySQL

## Structure du projet

- Dossier client : accueil, connexion, inscription, recherche de créneaux, mes rendez-vous
- Dossier admin : accueil entreprise, publication des créneaux, recherche des rendez-vous

## Base de données

Base : `gestion_de_rendez_vous`, avec les tables `users` et `creneaux`. Un créneau réservé est lié à un client grâce à la clé étrangère `user_id`.

## Installation

1. Installer XAMPP (ou WAMP) et démarrer Apache et MySQL.
2. Copier le dossier du projet dans le dossier `htdocs`.
3. Créer la base `gestion_de_rendez_vous` dans phpMyAdmin et importer le fichier SQL.
4. Configurer la connexion à la base dans les fichiers PHP.
5. Ouvrir `http://localhost/` suivi du nom du dossier dans le navigateur.

## Auteur

RAKOTOARISON Tiana Avotsoa, étudiante en Applied Computing, Maurice (University Of Mauritius).

## Licence

Tous droits réservés