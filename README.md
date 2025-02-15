# Projet Portfolio - Gestion des Utilisateurs et des Compétences

## Présentation du Projet
Ce projet est une application web développée en PHP & MySQL permettant aux utilisateurs de :
- [x] Gérer leur profil (inscription, connexion, mise à jour des informations).
- [x] Ajouter et modifier leurs compétences parmi celles définies par un administrateur.
- [x] Ajouter et gérer leurs projets (titre, description, image et lien).
- [x] Un administrateur peut gérer les compétences disponibles.

## Fonctionnalités Implémentées

### Authentification & Gestion des Comptes
- [x] Inscription avec validation des champs
- [x] Connexion sécurisée avec sessions et option "Se souvenir de moi"
- [x] Gestion des rôles (Admin / Utilisateur)
- [x] Mise à jour des informations utilisateur
- [x] Réinitialisation du mot de passe
- [x] Déconnexion sécurisée

### Gestion des Compétences
- [x] L’administrateur peut gérer les compétences proposées
- [x] Un utilisateur peut sélectionner ses compétences parmi celles disponibles
- [x] Niveau de compétence défini sur une échelle (débutant → expert)

### Gestion des Projets
- [x] Ajout, modification et suppression de projets
- [x] Chaque projet contient : Titre, Description, Image, Lien externe
- [x] Upload sécurisé des images avec restrictions de format et taille
- [x] Affichage structuré des projets

### Sécurité
- [x] Protection contre XSS, CSRF et injections SQL
- [x] Hachage sécurisé des mots de passe
- [x] Gestion des erreurs utilisateur avec affichage des messages et conservation des champs remplis
- [x] Expiration automatique de la session après inactivité

## Installation et Configuration

### Prérequis
- Serveur local (XAMPP, WAMP, etc.)
- PHP 8.x et MySQL
- Un navigateur moderne

### Étapes d’Installation
1. Cloner le projet sur votre serveur local :
   ```sh
   git clone https://github.com/thierry-mous/php_portfolio_Ortiz_Mousnier
   ```
2. Importer la base de données :
- Le fichier SQL pour initialiser la base de données se trouve dans config/database.sql.
- Pour l'importer dans votre environnement local :
- Ouvrez PhpMyAdmin et connectez-vous à votre interface.
- Créez une nouvelle base de données (par exemple, nommez-la portfolio).
- Accédez à l'onglet "Importer" dans la base de données créée.
- Sélectionnez le fichier database.sql à l'aide du bouton "Choisir un fichier".
- Cliquez sur "Exécuter" pour importer les tables et les données.
- Assurez-vous que les informations de connexion à la base de données dans le fichier includes/config.php correspondent à votre configuration locale (hôte, nom d'utilisateur, mot de passe, nom de la base).

3. Configurer la connexion à la base de données :
   Modifier le fichier `config/database.php` :
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'projetb2');
   define('DB_USER', 'projetb2');
   define('DB_PASS', 'password');
   define('DB_PORT', 3306);
   ```

4. Démarrer le serveur PHP et tester l'application :
   ```sh
   php -S localhost:8000
   ```
   Puis accéder à l'application via `http://localhost:8000`

## Comptes de Test

### Compte Administrateur
- **Email** : test1@test.com
- **Identifiant** : user1
- **Mot de passe** : password


### Compte Utilisateur
- **Email** : test2@test.com
- **identifiant** : user2
- **Mot de passe** : test

## Structure du Projet
```
/PHP_Portfolio/
│
├── assets/
│
├── config/
│
├── forms/
│
├── functions/
│
├── includes/
│
├── public/
│   ├── admin
│
├── templates/
│
├── uploads/
│   ├── profile_pictures/
│   └── projects/
│
└── README.md
```
```
assets/          -> Fichiers CSS et autres ressources front-end
config/          -> Fichiers de configuration (base de données, variables globales, etc.)
forms/           -> Gestion des formulaires (validation, traitement)
functions/       -> Fonctions PHP utilitaires
includes/        -> Fichiers inclus (headers, footers, etc.)
public/          -> Fichiers accessibles publiquement (images, fichiers JS/CSS compilés)
  ├── admin      -> Interfaces ou fichiers dédiés à l'administration
templates/       -> Templates HTML pour les différentes pages
uploads/         -> Fichiers uploadés par les utilisateurs
  ├── profile_pictures/ -> Images de profil
  └── projects/         -> Fichiers des projets
README.md        -> Documentation du projet

```

## Technologies Utilisées
- **Backend** : **php**
- **Frontend** : **php html css**
- **Sécurité** : **php**
- **Gestion du Projet** : **Trello**

## Licence
Ce projet est sous licence MIT.

## Contact
Une question ou un bug ? Contactez-nous :
nos email
Ortiz Morgane: morgane.ortiz@ynov.com
Mousnier Thierry: thierry.mousnier@ynov.com
