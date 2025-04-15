# FolioVision

## Description

**FolioVision** est une application de gestion financière conçue pour aider les utilisateurs à organiser leurs comptes, suivre leurs transactions et gérer leurs budgets. Développée en PHP avec une base de données MySQL, elle est destinée à fonctionner sur un serveur local comme XAMPP, offrant une interface moderne et intuitive.

## Fonctionnalités

- Gestion de divers types de comptes (bancaires, espèces, cryptomonnaies, investissements, etc.).
- Ajout, modification et suppression de transactions avec catégorisation.
- Création et suivi de budgets personnalisés.
- Personnalisation des préférences (thèmes, couleurs, etc.).
- Interface utilisateur responsive.
- Système d’authentification sécurisé (inscription, connexion, gestion de profil).

## Installation

### Prérequis

Avant de commencer, assurez-vous d’avoir installé :

- **XAMPP** (version 7.4 ou supérieure)
- Un navigateur web (Chrome, Firefox, etc.)

Vérifiez également que les services **Apache** et **MySQL** sont actifs dans XAMPP.

### Étapes d’installation

#### Configuration de la base de données

1. Ouvrez **phpMyAdmin** via `http://localhost/phpmyadmin` dans votre navigateur.
2. Créez une nouvelle base de données nommée `foliovision`.
3. Importez le fichier `database.sql` situé à la racine du projet :
   - Dans phpMyAdmin, cliquez sur l’onglet **Importer**.
   - Sélectionnez le fichier `database.sql` depuis votre ordinateur.
   - Cliquez sur **Exécuter** pour créer les tables et insérer les données initiales.
4. Vérifiez ou modifiez le fichier `config/database.php` pour correspondre à votre configuration locale. Voici le contenu par défaut :

   ```php
   <?php
   $host = 'localhost';
   $db_name = 'foliovision';
   $username = 'root';
   $password = '';
   try {
       $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
       $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch(PDOException $e) {
       echo "Erreur de connexion : " . $e->getMessage();
   }
   ```

   - Si vous avez défini un mot de passe pour l’utilisateur `root` dans MySQL, mettez à jour `$password`.
   - Les autres paramètres (`$host`, `$db_name`, `$username`) conviennent généralement pour une installation locale.

#### Configuration du serveur web

1. Placez le dossier du projet (`FolioVision V2`) dans le répertoire `htdocs` de XAMPP. Exemple :
   - Si XAMPP est installé dans `C:\xampp`, déplacez le dossier vers `C:\xampp\htdocs\FolioVision V2`.
2. Assurez-vous que le fichier `.htaccess` est présent à la racine du projet pour gérer les réécritures d’URL.

#### Accès au projet

1. Lancez les services **Apache** et **MySQL** depuis le panneau de contrôle XAMPP.
2. Ouvrez votre navigateur et accédez à :
   ```
   http://localhost/FolioVision/
   ```
3. Si tout est bien configuré, la page d’accueil (gérée par `index.php`) s’affichera.
4. En cas d’erreur, consultez le fichier `error.log` à la racine du projet.

## Structure du projet

Le projet est organisé ainsi :

- **`assets/`** : Fichiers CSS (`style.css`), JS (`script.js`), et images (icônes, favicon, etc.).
- **`config/`** : Fichiers de configuration :
  - `config.php` : Définit `BASE_PATH` et `BASE_URL`.
  - `database.php` : Gère la connexion à la base de données.
- **`includes/`** : En-têtes (`header.php`, `header_minimal.php`) et pied de page (`footer.php`).
- **`pages/`** : Pages principales, organisées en sous-dossiers :
  - `api/` : Scripts backend (ajout, modification, suppression).
  - `auth/` : Authentification (connexion, inscription, déconnexion).
  - `error/` : Pages d’erreur (404, 500).
  - `public/` : Pages accessibles sans connexion (à propos, contact, etc.).
  - `user/` : Pages pour utilisateurs connectés (comptes, tableau de bord, etc.).
- **`.htaccess`** : Réécritures d’URL.
- **`database.sql`** : Script SQL pour la base de données.
- **`index.php`** : Point d’entrée principal.

Pour plus de détails, voir `arborescence.txt` à la racine.

## Résolution des problèmes courants

- **Erreur de connexion à la base de données** :
  - Vérifiez les paramètres dans `config/database.php` (hôte, base, utilisateur, mot de passe).
- **Pages non affichées ou erreurs 404** :
  - Confirmez la présence de `.htaccess` et activez le module `mod_rewrite` dans Apache :
    1. Ouvre `httpd.conf` (`C:\xampp\apache\conf\`).
    2. Décommentez `#LoadModule rewrite_module modules/mod_rewrite.so` en supprimant `#`.
    3. Redémarrez Apache.
- **Erreur liée à l’URL** :
  - Assurez-vous que `BASE_URL` dans `config/config.php` correspond au chemin exact (par défaut : `/FolioVision/`).
