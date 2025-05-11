<?php
// Démarre la session pour accéder aux informations de l'utilisateur connecté
session_start();

// Inclut les fichiers nécessaires pour la configuration et la connexion à la base de données
include '../../config/config.php'; // Paramètres globaux comme les chemins ou constantes
include '../../config/database.php'; // Fournit l'objet PDO $conn pour la base de données

// Vérifie si l'utilisateur est connecté en testant la présence de 'user_id' dans la session
if (!isset($_SESSION['user_id'])) {
    // Si non connecté, renvoie une réponse JSON avec un statut d'erreur et un message
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    // Arrête l'exécution du script
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

// Prépare une requête SQL sécurisée pour récupérer le solde et la devise du compte spécifié
// - Filtre par account_id et user_id pour garantir que le compte appartient à l'utilisateur connecté
$stmt = $conn->prepare("SELECT balance, currency FROM accounts WHERE account_id = ? AND user_id = ?");
// Exécute la requête en passant l'identifiant du compte et de l'utilisateur comme paramètres
$stmt->execute([$account_id, $user_id]);
// Récupère les données du compte sous forme de tableau associatif (clés : balance, currency)
$account = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifie si des données ont été trouvées pour ce compte
if ($account) {
    // Si le compte existe, renvoie une réponse JSON avec un statut de succès, le solde et la devise
    echo json_encode([
        'status' => 'success',
        'balance' => $account['balance'],
        'currency' => $account['currency']
    ]);
} else {
    // Si aucun compte n'est trouvé (ou si l'utilisateur n'y a pas accès), renvoie une erreur JSON
    echo json_encode(['status' => 'error', 'message' => 'Compte non trouvé ou non autorisé']);
}
