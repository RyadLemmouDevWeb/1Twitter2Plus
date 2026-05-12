# 1Twitter2Plus – Social Network Platform

Une plateforme de réseau social complète et moderne inspirée de Twitter, développée en PHP natif + MySQL avec une interface Tailwind CSS premium.

**Version:** 1.2 (Production Ready)  
**Status:** ✅ Terminé

---

## 📋 Table des matières
1. [Vue d'ensemble](#vue-densemble)
2. [Architecture Technique](#architecture-technique)
3. [Structure du Projet](#structure-du-projet)
4. [Installation & Configuration](#installation--configuration)
5. [Fonctionnalités Détaillées](#fonctionnalités-détaillées)
6. [Routes & API](#routes--api)
7. [Modèle de Données (Schema)](#modèle-de-données-schema)
8. [Sécurité & Robustesse](#sécurité--robustesse)
9. [Perspectives d'Évolution](#perspectives-dévolution)

---

## Vue d'ensemble

**1Twitter2Plus** est un MVP fonctionnel de réseau social permettant une interaction riche entre utilisateurs. Le projet met l'accent sur la performance du PHP natif, la sécurité des données et une expérience utilisateur (UX) fluide.

**Points forts :**
- Flux d'actualité dynamique (Pour vous / Abonnements).
- Système de hashtags et tendances en temps réel.
- Notifications instantanées d'activité sociale.
- Messagerie privée et gestion des blocages.
- Recherche globale multicritères.
- Architecture MVC propre et modulaire.

---

## Architecture Technique

### Stack
| Couche | Technologie |
|--------|-------------|
| **Backend** | PHP 7.4+ (Logic, Routing, Security) |
| **Database** | MySQL 5.7+ (Persistent Storage) |
| **Frontend** | JS ES6 + Tailwind CSS 4.0 |
| **Styling** | Custom UI Refresh (Animated Backgrounds) |

### Design Patterns
- **MVC (Model-View-Controller) :** Séparation stricte de la logique métier, des données et de l'affichage.
- **Front Controller :** Point d'entrée unique (`index.php`) pour toutes les requêtes.
- **Singleton Pattern :** Connexion PDO partagée via `Database.php`.
- **Environment Driven :** Utilisation de fichiers `.env` pour la configuration locale.

---

## Structure du Projet

```text
1Twitter2Plus/
├── app/                        # Logique Applicative
│   ├── controllers/            # Traitement des actions (Routing logic)
│   │   ├── Account_Controller.php
│   │   ├── Block_Controller.php
│   │   ├── Bookmark_Controller.php
│   │   ├── Engagement_Controller.php
│   │   ├── Feed_Controller.php
│   │   ├── Follow_Controller.php
│   │   ├── Login_Controller.php
│   │   ├── Message_Controller.php
│   │   ├── Notification_Controller.php
│   │   ├── Search_Controller.php
│   │   └── Tweet_Controller.php
│   ├── models/                 # Interaction avec la base de données
│   │   ├── Account_Model.php
│   │   ├── Block_Model.php
│   │   ├── Bookmark_Model.php
│   │   ├── Database.php        # Connection Singleton & .env loader
│   │   ├── Feed_Model.php
│   │   ├── Follow_Model.php
│   │   ├── Hashtag_Model.php
│   │   ├── Login_Model.php
│   │   ├── Message_Model.php
│   │   ├── Notification_Model.php
│   │   └── Search_Model.php
│   ├── views/                  # Templates HTML / PHP
│   │   ├── Account_View.php
│   │   ├── Bookmark_View.php
│   │   ├── Feed_View.php
│   │   ├── Login_View.php
│   │   ├── Message_View.php
│   │   ├── Notification_View.php
│   │   ├── Search_View.php
│   │   ├── Tweet_View.php
│   │   └── 404.php
│   └── helpers/                # Utilitaires transversaux
│       └── Security.php        # CSRF, Password Policy, MIME Check
├── config/                     # Configuration et SQL Scripts
│   ├── common_database.sql     # Schéma de base
│   ├── seed_social_network.sql # Données de démo hachées (BCRYPT)
│   └── insert.sql              # Données de test initiales
├── public/                     # Racine Web (Seuls fichiers accessibles)
│   ├── index.php               # Routeur central
│   ├── css/                    # Tailwind Compiled Output
│   ├── lib/                    # Scripts JS clients
│   ├── assets/                 # Icônes et ressources graphiques
│   └── uploads/                # Stockage des médias (avatars, bannières)
└── .env                        # Configuration secrets (non versionné)
```

---

## Installation & Configuration

### 1. Base de Données
Importez le schéma puis les données de démonstration :
```bash
mysql -u root -p < config/common_database.sql
mysql -u root -p twitter < config/seed_social_network.sql
```

### 2. Variables d'Environnement
Créez un fichier `.env` à la racine :
```env
DB_HOST=127.0.0.1
DB_NAME=twitter
DB_USER=votre_user
DB_PASSWORD=votre_mdp
```

### 3. Serveur local
```bash
php -S localhost:5500 -t public
```

---

## Fonctionnalités Détaillées

### 🔐 Authentification & Profils
- **Hachage BCRYPT :** Tous les mots de passe sont sécurisés.
- **Édition de profil :** Mise à jour sécurisée du nom, bio, avatar et bannière.
- **Upload sécurisé :** Vérification du type MIME réel (finfo) et limitation de taille.

### 📝 Tweets & Interactions
- **Contenu riche :** Texte ≤ 140 caractères + jusqu'à 4 images par tweet.
- **Engagement :** Likes, Retweets, Réponses (Threads).
- **Signets (Bookmarks) :** Espace privé pour sauvegarder des tweets.

### 👥 Social & Modération
- **Suivi :** Système Follow/Unfollow asymétrique.
- **Blocage :** Possibilité de bloquer un utilisateur (masque tweets, messages et empêche le follow).
- **Notifications :** Système d'alertes visuelles pour chaque interaction sociale.

### 🔍 Discovery
- **Hashtags :** Extraction automatique via Regex lors de la publication.
- **Tendances :** Top 5 des tags les plus populaires basé sur l'activité réelle.
- **Recherche :** Moteur global filtrant les utilisateurs et tweets.

---

## Routes & API

| Chemin | Action | Description |
|--------|--------|-------------|
| `/` | `GET` | Redirection vers Feed |
| `/login` | `GET/POST` | Authentification |
| `/feed` | `GET/POST` | Fil d'actualité & Publication |
| `/search` | `GET` | Moteur de recherche |
| `/notifications` | `GET` | Alertes d'activité |
| `/bookmarks` | `GET` | Tweets sauvegardés |
| `/account` | `GET` | Profil (Public ou Personnel) |
| `/account/update`| `POST` | Mise à jour profil (Avatar/Bio/...) |
| `/messages` | `GET` | Liste des conversations |
| `/message` | `GET/POST` | Conversation spécifique |
| `/block` | `POST` | Bloquer un utilisateur |
| `/follow` | `POST` | Suivre un utilisateur |

---

## Modèle de Données (Schema)

Le schéma MySQL est optimisé avec des clés étrangères et des contraintes d'intégrité.

- **`user`** : Identité, hachage, chemins images, métadonnées profil.
- **`tweet`** : Contenu, auteur, parent_id (réponses), chemins média 1-4.
- **`follow`** : Table pivot (follower_id, followed_id).
- **`block_user`** : Table pivot (user_id, blocked_user_id).
- **`likes` / `retweet` / `bookmark`** : Tables d'engagement liées aux tweets.
- **`hashtag` / `tweet_hashtag`** : Gestion des tags et relations N-N.
- **`notification`** : Type d'événement, émetteur, destinataire, cible (tweet).
- **`message`** : Contenu, émetteur, destinataire, horodatage.
- **`login_attempts`** : Tracking par IP pour le Rate Limiting.

---

## Sécurité & Robustesse

### ✅ Protection des données
- **Injections SQL :** Utilisation systématique de `PDO::prepare()` et `bindValue()`.
- **Failles XSS :** Échappement via `htmlspecialchars()` à chaque affichage de donnée utilisateur.
- **CSRF :** Tokens générés par session et vérifiés sur chaque requête `POST`.

### ✅ Résilience système
- **Rate Limiting :** Limitation à 5 échecs de connexion par tranche de 15 minutes par IP.
- **Validation d'Upload :** Seuls les fichiers JPG, PNG, WEBP et GIF sont acceptés après analyse du contenu binaire.
- **Filtrage de Blocage :** La logique de blocage est appliquée au niveau SQL (`NOT EXISTS`) pour garantir l'étanchéité des données.

---

## Perspectives d'Évolution

- **Real-time Engine :** Passage de l'AJAX polling vers des WebSockets (Ratchet).
- **Analytics :** Implémentation de la table `impression` pour les statistiques de vue.
- **Performance :** Mise en cache des tendances via Redis.
- **Mobile App :** Création d'une API REST JSON dédiée.

---
**Dernière mise à jour :** Mai 2026  
**Licence :** Projet académique / Open Source