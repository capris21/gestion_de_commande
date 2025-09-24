# Agence Voyage – Application de Gestion

## Description du projet

Cette application web permet la gestion complète d’une agence de voyage :  
- Gestion des destinations, clients, réservations  
- Gestion des utilisateurs (administrateurs et caissiers)  
- Sécurité par authentification et gestion des rôles  
- Envoi automatique d’e-mails (confirmation de réservation, création d’utilisateur)  
- Interface moderne et responsive (Bootstrap)

---

## Technologies utilisées

- **PHP** (Programmation Web)
- **MySQL** (Base de données)
- **PDO** (Accès sécurisé à la base)
- **Bootstrap 5** (Interface utilisateur)
- **PHPMailer** (Envoi d’e-mails)
- **Git/GitHub** (Versionning et hébergement)

---

## Structure de la base de données

```sql
-- Script de création de la base agence_voyage

CREATE DATABASE IF NOT EXISTS agence_voyage CHARACTER SET utf8mb4;
USE agence_voyage;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'caissier') NOT NULL
);

CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telephone VARCHAR(20)
);

CREATE TABLE destinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pays VARCHAR(100) NOT NULL,
    ville VARCHAR(100) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    date_depart DATE NOT NULL,
    date_retour DATE NOT NULL,
    statut ENUM('en attente', 'validée', 'annulée') DEFAULT 'en attente'
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    destination_id INT NOT NULL,
    date_reservation DATE NOT NULL,
    utilisateur_id INT NOT NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (destination_id) REFERENCES destinations(id) ON DELETE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

CREATE TABLE logs_emails (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    sujet VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

---

## Instructions d'installation

1. **Cloner le projet**
   ```bash
   git clone https://github.com/capris21/agence_de_voyage.git
   ```

2. **Importer la base de données**
   - Ouvrir phpMyAdmin ou un terminal MySQL
   - Exécuter le script SQL ci-dessus (`agence_voyage.sql` fourni dans le projet)

3. **Configurer la connexion à la base**
   - Modifier le fichier `connexion.php` avec vos identifiants MySQL

4. **Installer les dépendances**
   - Installer [Composer](https://getcomposer.org/)
   - Installer PHPMailer :
     ```bash
     composer install
     ```

5. **Configurer PHPMailer**
   - Modifier les paramètres SMTP dans les fichiers d’envoi d’e-mail (`create.php`, etc.)

6. **Lancer le serveur**
   - Placer le dossier dans `htdocs` (XAMPP) ou configurer votre serveur web
   - Accéder à `http://localhost/agence_voyage/`

---

## Accès de test

- **Administrateur**
  - Email : `donfackcaline@gmail.com`
  - Mot de passe : `N9kymy8T`

- **Caissier**
  - Email : `dreydonfack@gmail.com`
  - Mot de passe : `yp5z0Daw`

*(Ces comptes sont à créer dans la base ou via l’interface d’administration)*

---

## Fonctionnalités principales

- Authentification sécurisée (admin/caissier)
- Gestion CRUD des utilisateurs (admin uniquement)
- Gestion CRUD des clients et destinations (admin et caissier)
- Gestion des réservations (admin et caissier)
- Envoi automatique d’e-mails (PHPMailer)
- Interface responsive et moderne

---

## Auteur

Projet réalisé dans le cadre de l’évaluation de Programmation Web PHP – Génie Logiciel, Trimestre 3.
