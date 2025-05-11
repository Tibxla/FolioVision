<?php
// Démarre la session pour accéder aux informations de l'utilisateur connecté stockées dans $_SESSION
session_start();

// Inclut les fichiers de configuration et de connexion à la base de données
include '../../config/config.php'; // Contient des constantes globales comme BASE_URL ou d'autres paramètres
include '../../config/database.php'; // Initialise la connexion PDO à la base de données via la variable $conn

// Vérifie si l'utilisateur est authentifié en testant la présence de 'user_id' dans la session
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, renvoie une erreur au format JSON et termine l'exécution
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

// Récupère l'identifiant de l'utilisateur connecté depuis la session
$user_id = $_SESSION['user_id'];

// Récupère les données envoyées par la méthode POST (probablement via un formulaire ou une requête AJAX)
$name = $_POST['category_name'] ?? null; // Nom de la catégorie à créer, null si non fourni
$parent_id = $_POST['parent_category_id'] ?? null; // ID de la catégorie parente, null si non fourni (pour sous-catégorie)

// Vérifie si le nom de la catégorie est fourni et non vide
if (empty($name)) {
    // Si le nom est absent ou vide, renvoie une erreur JSON et termine l'exécution
    echo json_encode(['status' => 'error', 'message' => 'Nom de la catégorie manquant']);
    exit;
}

// Si aucune catégorie parente n'est spécifiée (champ vide ou non fourni), force $parent_id à NULL
$parent_id = ($parent_id === '') ? null : $parent_id;

// Vérifie l'unicité du nom dans la base de données en fonction du contexte (catégorie principale ou sous-catégorie)
if ($parent_id === null) {
    // Cas d'une catégorie principale (sans parent)
    // Prépare une requête SQL pour compter les catégories existantes avec le même nom, soit spécifiques à l'utilisateur, soit globales (user_id IS NULL)
    $stmt = $conn->prepare("SELECT COUNT(*) FROM categories WHERE name = ? AND parent_id IS NULL AND (user_id = ? OR user_id IS NULL)");
    $stmt->execute([$name, $user_id]); // Exécute la requête avec le nom et l'ID utilisateur
} else {
    // Cas d'une sous-catégorie (avec une catégorie parente)
    // Prépare une requête SQL pour compter les sous-catégories avec le même nom sous la catégorie parente spécifiée
    $stmt = $conn->prepare("SELECT COUNT(*) FROM categories WHERE name = ? AND parent_id = ? AND (user_id = ? OR user_id IS NULL)");
    $stmt->execute([$name, $parent_id, $user_id]); // Exécute avec nom, ID parent et ID utilisateur
}

// Récupère le résultat de la requête (nombre d'occurrences trouvées)
$exists = $stmt->fetchColumn();

// Si une catégorie ou sous-catégorie avec ce nom existe déjà dans le même contexte, renvoie une erreur
if ($exists > 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Une catégorie avec ce nom existe déjà' . ($parent_id ? ' dans cette catégorie parente' : '') // Message adapté selon le contexte
    ]);
    exit;
}

// Si le nom est unique, insère la nouvelle catégorie dans la table 'categories'
$stmt = $conn->prepare("INSERT INTO categories (user_id, name, parent_id) VALUES (?, ?, ?)"); // Prépare la requête d'insertion
$stmt->execute([$user_id, $name, $parent_id]); // Insère avec l'ID utilisateur, le nom et l'ID parent (ou NULL)
$category_id = $conn->lastInsertId(); // Récupère l'ID de la catégorie nouvellement créée

// Renvoie une réponse JSON indiquant le succès avec l'ID de la nouvelle catégorie
echo json_encode(['status' => 'success', 'category_id' => $category_id]);
