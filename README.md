# FolioVision
Installation et configuration du projet FolioVision
Prérequis
Avant de commencer, assurez-vous d'avoir installé les éléments suivants :

XAMPP (version 7.4 ou supérieure)
Un navigateur web (Chrome, Firefox, etc.)
Assurez-vous également que les services Apache et MySQL sont en cours d'exécution dans XAMPP.

Configuration de la base de données
Ouvrez phpMyAdmin en accédant à http://localhost/phpmyadmin dans votre navigateur.
Créez une nouvelle base de données nommée foliovisionv2.
Importez le fichier database.sql situé à la racine du projet dans cette base de données. Pour ce faire :
Cliquez sur l'onglet Importer dans phpMyAdmin.
Sélectionnez le fichier database.sql depuis votre ordinateur.
Cliquez sur Exécuter pour importer les tables et les données initiales.
Vérifiez ou modifiez le fichier config/database.php pour vous assurer que les paramètres de connexion correspondent à votre configuration locale. Le contenu par défaut est :
php

Réduire

Envelopper

Copier
<?php
$host = 'localhost';
$db_name = 'foliovisionv2';
$username = 'root';
$password = '';
try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
Si vous avez défini un mot de passe pour l'utilisateur root dans MySQL, mettez à jour le champ $password avec ce mot de passe.
Les autres paramètres ($host, $db_name, $username) devraient convenir pour une installation locale standard.
Configuration du serveur web
Placez le dossier du projet (FolioVision V2) dans le dossier htdocs de votre installation XAMPP. Par exemple :
Si XAMPP est installé dans C:\xampp, déplacez ou copiez le dossier dans C:\xampp\htdocs\FolioVision V2.
Assurez-vous que le fichier .htaccess est présent à la racine du projet. Ce fichier configure les réécritures d'URL nécessaires au bon fonctionnement des pages.
Accès au projet
Démarrez les services Apache et MySQL via le panneau de contrôle XAMPP.
Ouvrez votre navigateur web et accédez à l'URL suivante :
http://localhost/FolioVision%20V2/
(Note : %20 représente l'espace dans le nom du dossier. Vous pouvez aussi renommer le dossier en FolioVisionV2 pour simplifier l'URL en http://localhost/FolioVisionV2/.)
Si tout est correctement configuré, vous devriez voir la page d'accueil du projet (gérée par index.php).
En cas d'erreur, consultez le fichier error.log à la racine du projet pour identifier le problème.
Structure du projet
Le projet est organisé comme suit :

assets/ : Contient les fichiers CSS (style.css), JS (script.js, screipt.js), et images (icônes, favicon, etc.).
config/ : Contient les fichiers de configuration :
config.php : Définit les constantes BASE_PATH et BASE_URL.
database.php : Gère la connexion à la base de données.
includes/ : Contient les fichiers d'en-tête (header.php, header_minimal.php) et de pied de page (footer.php).
pages/ : Contient les pages principales du projet, organisées en sous-dossiers :
api/ : Scripts pour les opérations backend (ajout, modification, suppression de données).
auth/ : Pages d'authentification (connexion, inscription, déconnexion).
error/ : Pages d'erreur (404, 500).
public/ : Pages accessibles sans connexion (à propos, contact, etc.).
user/ : Pages réservées aux utilisateurs connectés (comptes, tableau de bord, etc.).
.htaccess : Configure les réécritures d'URL.
database.sql : Script SQL pour créer les tables et insérer les données initiales.
index.php : Point d'entrée principal du projet.
Pour une vue détaillée, consultez le fichier arborescence.txt à la racine.

Résolution des problèmes courants
Erreur de connexion à la base de données : Vérifiez les paramètres dans config/database.php (hôte, nom de la base, utilisateur, mot de passe).
Pages non affichées ou erreurs 404 :
Assurez-vous que le fichier .htaccess est présent et que le module de réécriture d'URL est activé dans Apache.
Pour activer le module mod_rewrite :
Ouvrez le fichier httpd.conf (situé dans C:\xampp\apache\conf\).
Recherchez la ligne #LoadModule rewrite_module modules/mod_rewrite.so.
Supprimez le # pour décommenter la ligne.
Redémarrez Apache via le panneau XAMPP.
Erreur liée à l'URL : Vérifiez que BASE_URL dans config/config.php correspond au chemin exact dans htdocs (par défaut : /FolioVision V2/).
