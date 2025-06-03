# Gestionnaire de Cours

Projet web complet réalisé dans le cadre d'un devoir BTS SIO.  
Ce site permet la gestion des cours, de la communication, des documents et des utilisateurs via une interface web responsive. Il est conçu pour les rôles suivants : **admin**, **professeur**, et **élève**.

---

## 🔧 Technologies utilisées

- HTML5 / CSS3 (thème sombre responsive)
- JavaScript (DOM + Fetch API)
- PHP (traitement serveur + session)
- MySQL (base de données)
- Font Awesome (icônes)

---

## 🗂️ Structure du projet

gestionnaire-de-cours/
│
├── css/
│ └── styles-v2.css → Styles globaux du site (responsive, thème sombre)
│
├── js/
│ └── interface.js → Script principal : gestion onglets, requêtes, DOM
│
├── php/
│ ├── config.php → Connexion à la base de données
│ ├── login.php → Authentification utilisateur
│ ├── logout.php → Déconnexion
│ ├── get_emploi.php → Récupération emploi du temps (semaine + date)
│ ├── set_emploi.php → Modification emploi du temps (admin uniquement)
│ ├── get_chat.php → Affichage des messages (chat général)
│ ├── get_users.php → Affichage des utilisateurs (admin uniquement)
│ ├── send_chat.php → Envoi de message (admin uniquement)
│ ├── delete_chat.php → Suppression de message (admin uniquement)
│ ├── get_docs.php → Affichage des documents personnels
│ ├── upload_doc.php → Upload de document (admin vers un utilisateur)
│ ├── set_users.php → Récupération des utilisateurs (admin uniquement)
│ ├── set_emploi.php → Récupération du planning (admin uniquement)
│ ├── add_user.php → Ajout d’un utilisateur (admin uniquement)
│ ├── delete_user.php → Suppression utilisateur (admin uniquement)
│ └── init_bd.sql → Script de création de la base de données
├── sql/
│ ├── init_db.sql
├── index.html → Page de connexion
├── interface.html → Interface principale après connexion
└── README.md → Ce fichier


---

## 👤 Rôles & Accès

### Admin
- Accès au planning : ✔️ Lecture + modification
- Accès au chat : ✔️ Lecture, envoi et suppression
- Accès aux documents : ✔️ Tous les documents
- Gestion des utilisateurs : ✔️ Voir, ajouter, supprimer

### Professeur
- Accès au planning : ✔️ Lecture seulement
- Accès au chat : ✔️ Lecture uniquement
- Accès aux documents : ✔️ Ses propres documents
- Gestion des utilisateurs : ❌ Non autorisé

### Élève
- Accès au planning : ✔️ Lecture seulement
- Accès au chat : ✔️ Lecture uniquement
- Accès aux documents : ✔️ Ses propres documents
- Gestion des utilisateurs : ❌ Non autorisé

## 📆 Fonctionnalités

### Connexion sécurisée

- Formulaire HTML (login + mot de passe)
- Hashage des mots de passe (`password_hash`)
- Vérification côté serveur et création de session

### Interface à onglets (responsive)

- **Planning** : visualisation hebdomadaire des cours par date + heure
  - Admin : peut modifier chaque cellule via un bouton ✏️ contextuel
- **Communication** : chat général
  - Visible par tous
  - Seul l’admin peut envoyer ou supprimer un message
- **Documents** :
  - Chaque utilisateur ne voit que ses propres documents
  - L’admin peut déposer un fichier vers un utilisateur précis
- **Utilisateurs** :
  - Visible uniquement par l’admin
  - Il peut :
    - Voir tous les utilisateurs (nom + rôle)
    - Ajouter un nouvel utilisateur
    - Supprimer un utilisateur

### Responsive Design

- Navigation inférieure fixe sur mobile
- Affichage adaptatif des contenus
- Design sombre avec contrastes accessibles

---

## ✅ Lancer le projet localement

je comte le mettre en ligne, d'ici ce soir

---

## 📌 Remarques

- Les sessions PHP sont utilisées pour sécuriser les accès.
- Toutes les opérations critiques sont protégées par des vérifications de rôle côté serveur.
- Aucun framework n’est utilisé pour garantir la simplicité du projet.

---

## 👨‍💻 Auteurs

Projet réalisé par [OBELEMBIA Smitch Chrioni] – BTS SIO 1
