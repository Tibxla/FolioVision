<?php
// Démarre la session pour accéder aux informations stockées, comme l'identifiant de l'utilisateur
session_start();

// Inclut les fichiers nécessaires pour la configuration globale et la connexion à la base de données
include '../../config/config.php'; // Contient des paramètres comme le chemin de base ou des constantes
include '../../config/database.php'; // Fournit l'objet PDO $conn pour interagir avec la base de données

// Vérifie si l'utilisateur est connecté en regardant si 'user_id' existe dans la session
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, renvoie une réponse JSON indiquant une erreur et arrête le script
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

// Récupère l'identifiant de l'utilisateur connecté à partir de la session
$user_id = $_SESSION['user_id'];

// Récupère l'ID de la catégorie principale depuis les paramètres de l'URL (GET), ou null si non fourni
$main_category_id = $_GET['main_category_id'] ?? null;

// Vérifie si l'ID de la catégorie principale est présent et non vide
if (empty($main_category_id)) {
    // Si l'ID est absent ou vide, renvoie une erreur JSON et arrête le script
    echo json_encode(['status' => 'error', 'message' => 'ID de la catégorie principale manquant']);
    exit;
}

// Prépare une requête SQL sécurisée pour récupérer les sous-catégories
// - Sélectionne l'ID et le nom des sous-catégories
// - Filtre par parent_id (catégorie principale) et par user_id (spécifique à l'utilisateur ou global)
// - Trie les résultats par nom dans l'ordre alphabétique croissant
$stmt = $conn->prepare("SELECT category_id, name FROM categories WHERE parent_id = ? AND (user_id = ? OR user_id IS NULL) ORDER BY name ASC");
// Exécute la requête en passant l'ID de la catégorie principale et l'ID de l'utilisateur comme paramètres
$stmt->execute([$main_category_id, $user_id]);
// Récupère toutes les sous-catégories sous forme de tableau associatif (clés = noms des colonnes)
$subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Renvoie une réponse JSON avec un statut de succès et la liste des sous-catégories
echo json_encode(['status' => 'success', 'subcategories' => $subcategories]);