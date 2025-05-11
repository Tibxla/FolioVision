<?php
// Inclut le fichier de configuration pour accéder à la constante BASE_URL
include_once '../../config/config.php';

// Vérifie si une session est déjà active, sinon la démarre
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Supprime toutes les variables de la session (ex. user_id, username)
session_unset();
// Détruit complètement la session pour déconnecter l'utilisateur
session_destroy();

// Redirige l'utilisateur vers la page d'accueil
header("Location: " . BASE_URL . "index.php");
// Termine le script pour éviter toute exécution supplémentaire
exit;


