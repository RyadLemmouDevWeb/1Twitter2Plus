# My_Twitter

## Description
My_Twitter est un projet de réseau social inspiré de Twitter, développé en collaboration par **Tom, Pavel, Mathéo et Ryad**. Ce projet vise à permettre aux utilisateurs de publier des messages, d'interagir avec les publications des autres et d'explorer du contenu en temps réel.

## Technologies utilisées
- **PHP** (Back-end)
- **Tailwind CSS** (Stylisation)
- **JavaScript** (Interactions dynamiques)
- **MySQL** (Base de données)

## Installation et exécution

### 1. Cloner le dépôt
```bash
git clone git@github.com:RyadLemmouDevWeb/1Twitter2Plus.git
```

### 2. Lancer le serveur PHP
Depuis le dossier `/public`, exécutez la commande suivante :
```bash
php -S localhost:5500
```
Le projet sera accessible sur `http://localhost:5500`.

### 3. Compiler Tailwind CSS
Avant de commencer, Tailwind CSS doit être compilé au moins une fois. Exécutez :
```bash
npx @tailwindcss/cli -i ./public/css/input.css -o ./public/css/output.css --watch
```
Cette commande mettra à jour le fichier `output.css` à chaque modification du fichier `input.css`.

## Fonctionnalités
✅ Création et authentification des utilisateurs
✅ Publication et suppression de tweets
✅ Fil d'actualité en temps réel
✅ Interface responsive avec Tailwind CSS
