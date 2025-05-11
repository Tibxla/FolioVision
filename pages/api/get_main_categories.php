<?php
// Démarre la session pour accéder aux informations de l'utilisateur
session_start();

// Inclut les fichiers de configuration et de connexion à la base de données
include '../../config/config.php'; // Paramètres globaux
include '../../config/database.php'; // Connexion PDO via $conn

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si non connecté, renvoie une erreur JSON et stoppe l'exécution
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

// Récupère l'ID de l'utilisateur connecté depuis la session
$user_id = $_SESSION['user_id'];

// Prépare une requête SQL pour sélectionner les catégories principales (parent_id IS NULL)
// Inclut les catégories spécifiques à l'utilisateur (user_id = ?) et les catégories globales (user_id IS NULL)
$stmt = $conn->prepare("SELECT category_id, name FROM categories WHERE parent_id IS NULL AND (user_id = ? OR user_id IS NULL) ORDER BY name ASC");
// Exécute la requête avec l'ID de l'utilisateur
$stmt->execute([$user_id]);
// Récupère toutes les catégories principales sous forme de tableau associatif
$main_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Renvoie les catégories principales dans une réponse JSON
echo json_encode(['status' => 'success', 'main_categories' => $main_categories]);
