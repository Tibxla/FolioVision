<?php
// Démarre la session pour accéder aux données de l'utilisateur connecté
session_start();

// Inclut les fichiers de configuration et de connexion à la base de données
include '../../config/config.php'; // Paramètres globaux
include '../../config/database.php'; // Fournit l'objet PDO $conn pour la base de données

// Vérifie si l'utilisateur est connecté en testant la présence de 'user_id' dans la session
if (!isset($_SESSION['user_id'])) {
    // Si non connecté, renvoie une erreur JSON et arrête le script
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

// Récupère l'identifiant de l'utilisateur connecté depuis la session
$user_id = $_SESSION['user_id'];

// Récupère les données envoyées via une requête (souvent AJAX) au format JSON depuis le flux d'entrée
$input = json_decode(file_get_contents('php://input'), true);
// Extrait l'identifiant de la transaction à dupliquer, ou null si non fourni
$transaction_id = $input['transaction_id'] ?? null;

// Ajoute une entrée dans les logs pour faciliter le débogage (affiche l'ID reçu)
error_log("Transaction ID reçu : " . $transaction_id);

// Vérifie si l'identifiant de la transaction a été fourni
if (empty($transaction_id)) {
    // Si absent ou vide, renvoie une erreur JSON et arrête le script
    echo json_encode(['status' => 'error', 'message' => 'ID de transaction manquant']);
    exit;
}

// Prépare une requête SQL pour récupérer les détails de la transaction à dupliquer
// - Joint la table 'transactions' avec 'accounts' pour obtenir la devise du compte
// - Filtre par transaction_id et user_id pour s'assurer que la transaction appartient à l'utilisateur
$stmt = $conn->prepare("
    SELECT t.*, a.currency
    FROM transactions t
    JOIN accounts a ON t.account_id = a.account_id
    WHERE t.transaction_id = ? AND a.user_id = ?
");
// Exécute la requête avec l'ID de la transaction et l'ID de l'utilisateur
$stmt->execute([$transaction_id, $user_id]);
// Récupère les données sous forme de tableau associatif (toutes les colonnes de 'transactions' + 'currency')
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifie si la transaction existe et appartient à l'utilisateur
if (!$transaction) {
    // Si aucune transaction n'est trouvée, renvoie une erreur JSON et arrête le script
    echo json_encode(['status' => 'error', 'message' => 'Transaction non trouvée ou non autorisée']);
    exit;
}

// Prépare les données pour la nouvelle transaction dupliquée
$account_id = $transaction['account_id']; // ID du compte (identique à la transaction originale)
$amount = $transaction['amount']; // Montant de la transaction (inchangé)
$type = $transaction['type']; // Type (crédit ou débit, inchangé)
$transaction_date = date('Y-m-d'); // Nouvelle date de la transaction (date actuelle)
$payment_method = $transaction['payment_method']; // Méthode de paiement (inchangée)
$category_id = $transaction['category_id']; // Catégorie de la transaction (inchangée)
$description = $transaction['description']; // Description (inchangée)
$currency = $transaction['currency']; // Devise du compte (inchangée)

// Bloc try-catch pour gérer les erreurs de base de données
try {
    // Démarre une transaction SQL pour garantir l'intégrité des opérations (insertion + mise à jour)
    $conn->beginTransaction();

    // Prépare une requête d'insertion pour créer une nouvelle transaction dans la table 'transactions'
    $stmt = $conn->prepare("
        INSERT INTO transactions (account_id, category_id, amount, transaction_date, value_date, type, payment_method, description)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    // Exécute l'insertion avec les données de la transaction dupliquée
    $stmt->execute([$account_id, $category_id, $amount, $transaction_date, $transaction_date, $type, $payment_method, $description]);

    // Prépare une requête pour récupérer le solde actuel du compte concerné
    $stmt = $conn->prepare("SELECT balance FROM accounts WHERE account_id = ?");
    $stmt->execute([$account_id]);
    // Récupère le solde actuel sous forme de valeur unique
    $current_balance = $stmt->fetchColumn();

    // Calcule le nouveau solde en fonction du type de transaction
    // - Si c'est un crédit, ajoute le montant au solde actuel
    // - Si c'est un débit, soustrait le montant du solde actuel
    $new_balance = ($type === 'credit') ? $current_balance + $amount : $current_balance - $amount;
    // Prépare une requête pour mettre à jour le solde du compte dans la table 'accounts'
    $stmt = $conn->prepare("UPDATE accounts SET balance = ? WHERE account_id = ?");
    $stmt->execute([$new_balance, $account_id]);

    // Valide la transaction SQL si toutes les opérations ont réussi
    $conn->commit();
    // Renvoie une réponse JSON indiquant que la duplication a réussi
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    // En cas d'erreur (ex. problème de base de données), annule toutes les modifications
    $conn->rollBack();
    // Enregistre l'erreur dans les logs pour le débogage
    error_log("Erreur PDO : " . $e->getMessage());
    // Renvoie une erreur JSON avec le message de l'exception
    echo json_encode(['status' => 'error', 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
}
