<?php
// Démarre la session pour accéder aux données de l'utilisateur connecté
session_start();

// Inclut les fichiers de configuration et de connexion à la base de données
include '../../config/config.php'; // Définit des constantes ou paramètres globaux
include '../../config/database.php'; // Fournit la connexion PDO via $conn

// Vérifie si l'utilisateur est authentifié en cherchant 'user_id' dans la session
if (!isset($_SESSION['user_id'])) {
    // Si non authentifié, renvoie une erreur JSON et termine le script
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

// Récupère l'identifiant de l'utilisateur connecté depuis la session
$user_id = $_SESSION['user_id'];

// Prépare une requête SQL pour sélectionner les catégories de l'utilisateur
// - Récupère l'ID et le nom des catégories
// - Filtre uniquement les catégories créées par cet utilisateur (WHERE user_id = ?)
$stmt = $conn->prepare("SELECT category_id, name FROM categories WHERE user_id = ?");
// Exécute la requête avec l'ID de l'utilisateur comme paramètre
$stmt->execute([$user_id]);
// Récupère toutes les catégories sous forme de tableau associatif
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Renvoie une réponse JSON avec un statut de succès et la liste des catégories
echo json_encode(['status' => 'success', 'categories' => $categories]);