<?php
// Définit l’adresse du serveur de base de données (ici, localhost pour un environnement local)
$host = 'localhost';
// Nom de la base de données spécifique à l’application FolioVision
$db_name = 'foliovision';
// Nom d’utilisateur pour la connexion à la base (root est typique en local)
$username = 'root';
// Mot de passe pour la connexion (vide ici, ce qui est courant en local mais risqué en production)
$password = '';
// Bloc try-catch pour gérer la connexion et les erreurs potentielles
try {
    // Crée une instance PDO pour se connecter à MySQL avec les paramètres spécifiés
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    // Configure PDO pour lever des exceptions en cas d’erreur SQL, facilitant le débogage
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Si la connexion échoue, affiche un message d’erreur avec les détails de l’exception
    echo "Erreur de connexions : " . $e->getMessage();
}
