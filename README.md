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

**Note importante :** Si vous réinstallez ou mettez à jour le projet, assurez-vous de vous déconnecter de tout compte FolioVision existant avant de commencer. Le processus d’installation recrée la base de données, et les données utilisateurs existantes seront perdues, ce qui pourrait provoquer des bugs si vous restez connecté.

### Prérequis

Avant de commencer, assurez-vous d’avoir installé :

- **XAMPP** (version 7.4 ou supérieure)
- Un navigateur web (Chrome, Firefox, etc.)

Vérifiez également que les services **Apache** et **MySQL** sont actifs dans XAMPP.

### Étapes d’installation

#### Configuration du serveur web
1. Téléchargez le ZIP du projet depuis le dépôt GitHub et extrayez-le. Le dossier créé sera probablement nommé `Foliovision-master` ou quelque chose de similaire, selon la convention de nommage de GitHub.
2. Pour éviter des problèmes avec l'URL attendue (comme `http://localhost/Foliovision/`) renommez le dossier extrait en `FolioVision` (il pourrait être nommé `FolioVision-master` ou similaire par défaut).
3. Placez le dossier du projet (`FolioVision`) dans le répertoire `htdocs` de XAMPP. Exemple :
   - Si XAMPP est installé dans `C:\xampp`, déplacez le dossier vers `C:\xampp\htdocs\FolioVision`.
4. Assurez-vous que :
   - Le dossier `Foliovision` est directement dans `htdocs` (pas dans un sous-dossier supplémentaire).
   - Le fichier `index.php` se trouve à la racine de `C:\xampp\htdocs\Foliovision\`.
Si ce n’est pas le cas, vérifiez que vous avez bien déplacé le bon dossier.
5. Assurez-vous que le fichier `.htaccess` est présent à la racine du projet pour gérer les réécritures d’URL.

#### Configuration de la base de données

1. Ouvrez **phpMyAdmin** via `http://localhost/phpmyadmin` dans votre navigateur.
2. Importez le fichier `database.sql` situé à la racine du projet :
   - Dans phpMyAdmin, cliquez sur l’onglet **Importer**.
   - Sélectionnez le fichier `database.sql` depuis votre ordinateur.
   - Cliquez sur **Exécuter** Cela créera la base de données foliovision, configurera les tables nécessaires et insérera les données initiales. Si une base de données foliovision existe déjà, elle sera supprimée et recréée.
**Avertissement :** L’importation du fichier SQL supprimera toute base de données foliovision existante ainsi que ses données. Sauvegardez les données importantes avant de continuer.
3. Vérifiez ou modifiez le fichier `config/database.php` pour correspondre à votre configuration locale. Voici le contenu par défaut :

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
