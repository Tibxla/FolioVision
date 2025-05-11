<?php
// Démarre la session pour accéder aux données de l'utilisateur connecté
session_start();

// Inclut les fichiers de configuration et de connexion à la base de données
include '../../config/config.php'; // Contient les paramètres globaux
include '../../config/database.php'; // Fournit l'objet PDO $conn pour interagir avec la base

// Vérifie si l'utilisateur est connecté en testant la présence de 'user_id' dans la session
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, renvoie une erreur JSON et arrête le script
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit;
}

// Récupère l'identifiant de l'utilisateur connecté depuis la session
$user_id = $_SESSION['user_id'];

// Récupère l'identifiant du compte depuis les paramètres GET, ou null si non fourni
$account_id = $_GET['account_id'] ?? null;

// Vérifie si l'identifiant du compte a été fourni dans la requête
if (empty($account_id)) {
    // Si l'identifiant est absent ou vide, renvoie une erreur JSON et arrête le script
    echo json_encode(['status' => 'error', 'message' => 'ID du compte manquant']);
    exit;
}

// Prépare une requête SQL sécurisée pour récupérer les détails du compte spécifié
// - Sélectionne le nom, le type, le sous-type bancaire, le solde et la devise
// - Filtre par account_id et user_id pour s'assurer que le compte appartient à l'utilisateur
$stmt = $conn->prepare("SELECT account_name, account_type, bank_subtype, balance, currency FROM accounts WHERE account_id = ? AND user_id = ?");
// Exécute la requête en passant l'identifiant du compte et de l'utilisateur comme paramètres
$stmt->execute([$account_id, $user_id]);
// Récupère les données sous forme de tableau associatif (clés : account_name, account_type, etc.)
$account = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifie si des données ont été trouvées pour ce compte
if ($account) {
    // Si le compte existe, renvoie une réponse JSON avec un statut de succès et les détails du compte
    echo json_encode(['status' => 'success', 'account' => $account]);
} else {
    // Si aucun compte n'est trouvé (ou si l'utilisateur n'y a pas accès), renvoie une erreur JSON
    echo json_encode(['status' => 'error', 'message' => 'Compte non trouvé ou non autorisé']);
}
