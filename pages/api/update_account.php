<?php
// Démarre la session pour accéder aux données de l'utilisateur
session_start();

// Inclut les fichiers de configuration et de connexion à la base de données
include '../../config/config.php'; // Paramètres globaux
include '../../config/database.php'; // Connexion PDO ($conn)

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si non connecté, renvoie une erreur JSON et arrête le script
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit;
}

// Récupère l'identifiant de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Vérifie que la requête utilise la méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données envoyées pour mettre à jour le compte
    $account_id = $_POST['account_id'] ?? null; // ID du compte à modifier
    $account_name = $_POST['account_name'] ?? null; // Nouveau nom du compte
    $account_type = $_POST['account_type'] ?? null; // Type de compte (ex. bancaire)
    $bank_subtype = $_POST['bank_subtype'] ?? null; // Sous-type (ex. compte courant)
    $balance = $_POST['balance'] ?? null; // Nouveau solde
    $currency = $_POST['currency'] ?? null; // Devise du compte

    // Vérifie que tous les champs obligatoires sont remplis
    if (empty($account_id) || empty($account_name) || empty($account_type) || !isset($balance) || $balance === '' || empty($currency)) {
        // Si un champ manque, renvoie une erreur
        echo json_encode(['status' => 'error', 'message' => 'Tous les champs sont requis']);
        exit;
    }
    // Pour un compte bancaire, le sous-type est obligatoire
    if ($account_type === 'bank' && empty($bank_subtype)) {
        // Si manquant, renvoie une erreur
        echo json_encode(['status' => 'error', 'message' => 'Le sous-type est requis pour les comptes bancaires']);
        exit;
    }
    // Vérifie que le solde est un nombre valide
    if (!is_numeric($balance)) {
        // Si non numérique, renvoie une erreur
        echo json_encode(['status' => 'error', 'message' => 'Le solde doit être un nombre']);
        exit;
    }

    // Prépare et exécute la mise à jour du compte dans la table 'accounts'
    $stmt = $conn->prepare("UPDATE accounts SET account_name = ?, account_type = ?, bank_subtype = ?, balance = ?, currency = ? WHERE account_id = ? AND user_id = ?");
    if ($stmt->execute([$account_name, $account_type, $bank_subtype, $balance, $currency, $account_id, $user_id])) {
        // Si la mise à jour réussit, renvoie un message de succès
        echo json_encode(['status' => 'success', 'message' => 'Compte modifié avec succès']);
    } else {
        // Si la mise à jour échoue, renvoie une erreur
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour']);
    }
} else {
    // Si la méthode n'est pas POST, renvoie une erreur
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
}
