# OpenClassrooms
- Développeur d'application
- Parcours PHP/Symfony
- Projet 6

## Développez de A à Z le site communautaire SnowTricks

### À propos
Bienvenu(e) sur ce dépôt, qui regroupe l’ensemble de mon travail réalisé dans le cadre du sixième projet d’OpenClassrooms, intitulé "Développez de A à Z une application web dynamique". Vous trouverez ici les instructions nécessaires pour installer et exécuter le projet, ainsi que la base de données et sa structure, conçues pour assurer son bon fonctionnement.

Dans le dossier ressources/diagrams, vous pourrez également consulter les diagrammes UML réalisés lors de la phase de conception, ainsi que le rapport d’analyse de la qualité du code, accessible via le bouton SymfonyInsight situé ci-dessous.

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/d38ae93178b34c72a29d40d03481ad41)](https://app.codacy.com/gh/LanchesThomas/snowtricks/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

---

# 🚀 Installation du Projet Symfony 7 avec PostgreSQL

Ce projet utilise **Symfony 7** et **PostgreSQL** comme base de données. Suivez les étapes ci-dessous pour l'installer et importer les données.

---

## 📌 Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- [PHP 8.2+](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/download/)
- [Symfony CLI](https://symfony.com/download)
- [PostgreSQL 14+](https://www.postgresql.org/download/)
- [pgAdmin ou psql (PostgreSQL CLI)](https://www.pgadmin.org/)

---

## 1️⃣ Cloner le projet

Dans un terminal, exécutez :

```bash
git clone https://github.com/LanchesThomas/snowtricks.git
cd snowtricks
```

## 2️⃣ Installer les dépendances Symfony

```bash
composer install
```

## 3️⃣ Configurer l'environnement

Copiez le fichier .env et renommez-le en .env.local :

```bash
cp .env .env.local
```

Puis modifiez la ligne de connexion à la base de données :

```bash
DATABASE_URL="postgresql://utilisateur:motdepasse@127.0.0.1:5432/snowtricks?serverVersion=16&charset=utf8"
```

Remplacez :

- utilisateur → votre utilisateur PostgreSQL (postgres par défaut).
- motdepasse → votre mot de passe PostgreSQL.

## 4️⃣ Créer la base de données

```bash
symfony console doctrine:database:create
```

## 5️⃣ Importer la base de données

### Méthode 1 : avec psql (terminal)

Si vous utilisez psql, exécutez :

```bash
psql -U utilisateur -d snowtricks -f ressources/snowtricks_db.sql
```

### Méthode 3 : avec pgAdmin

1. Ouvrez pgAdmin et connectez-vous à PostgreSQL.
2. Sélectionnez la base de données créée.
3. Cliquez sur Query Tool.
4. Ouvrez le fichier snowtricks_db.sql et exécutez-le.

## 6️⃣ Démarrer le serveur Symfony

Une fois la base de données importée, lancez le serveur Symfony :

```bash
symfony serve
```

Le projet est maintenant accessible sur http://127.0.0.1:8000 🚀