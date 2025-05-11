<?php
// Démarre la session pour accéder aux données de l'utilisateur connecté
session_start();

// Charge les fichiers de configuration et de connexion à la base de données
include '../../config/config.php'; // Paramètres globaux
include '../../config/database.php'; // Connexion PDO via $conn

// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
    // Si non connecté, renvoie une erreur JSON et arrête l'exécution
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

// Récupère l'identifiant de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Récupère les données envoyées via la méthode POST (souvent via un formulaire ou AJAX)
$account_id = $_POST['account_id'] ?? null; // ID du compte concerné
$amount = $_POST['amount'] ?? null; // Montant de la transaction
$type = $_POST['type'] ?? null; // Type de transaction (crédit ou débit)
$transaction_date = $_POST['transaction_date'] ?? null; // Date de la transaction
$payment_method = $_POST['payment_method'] ?? null; // Méthode de paiement
$main_category_id = $_POST['main_category_id'] ?? null; // Catégorie principale
$sub_category_id = $_POST['category_id'] ?? null; // Sous-catégorie (optionnelle)
$description = $_POST['description'] ?? null; // Description (optionnelle)
$currency = $_POST['currency'] ?? null; // Devise de la transaction

// Vérifie que tous les champs obligatoires sont remplis
if (empty($account_id) || empty($amount) || empty($type) || empty($transaction_date) || empty($payment_method) || empty($currency) || empty($main_category_id)) {
    // Si un champ obligatoire manque, renvoie une erreur JSON et arrête l'exécution
    echo json_encode(['status' => 'error', 'message' => 'Champs obligatoires manquants']);
    exit;
}

// Détermine l'identifiant de la catégorie à utiliser
// - Si une sous-catégorie est fournie, elle est prioritaire ; sinon, utilise la catégorie principale
$category_id = !empty($sub_category_id) ? $sub_category_id : $main_category_id;

// Vérifie que le compte existe, appartient à l'utilisateur et que la devise correspond
$stmt = $conn->prepare("SELECT currency, balance FROM accounts WHERE account_id = ? AND user_id = ?");
$stmt->execute([$account_id, $user_id]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);
// Si le compte n'existe pas ou si la devise ne correspond pas, renvoie une erreur
if (!$account || $account['currency'] !== $currency) {
    echo json_encode(['status' => 'error', 'message' => 'Compte non autorisé ou devise incohérente']);
    exit;
}

// Calcule le nouveau solde du compte en fonction du type de transaction
$current_balance = $account['balance'];
$new_balance = ($type === 'credit') ? $current_balance + $amount : $current_balance - $amount;

// Bloc try-catch pour gérer les opérations sur la base de données
try {
    // Démarre une transaction SQL pour garantir que la mise à jour du solde et l'insertion soient atomiques
    $conn->beginTransaction();

    // Met à jour le solde du compte avec la nouvelle valeur calculée
    $stmt = $conn->prepare("UPDATE accounts SET balance = ? WHERE account_id = ?");
    $stmt->execute([$new_balance, $account_id]);

    // Insère la nouvelle transaction dans la table 'transactions'
    $stmt = $conn->prepare("INSERT INTO transactions (account_id, category_id, amount, transaction_date, value_date, type, payment_method, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    // Utilise la même date pour transaction_date et value_date
    $stmt->execute([$account_id, $category_id, $amount, $transaction_date, $transaction_date, $type, $payment_method, $description]);

    // Valide la transaction SQL si toutes les opérations ont réussi
    $conn->commit();
    // Renvoie une réponse JSON de succès
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    // En cas d'erreur, annule toutes les modifications effectuées
    $conn->rollBack();
    // Renvoie une erreur JSON avec le message de l'exception
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l’ajout : ' . $e->getMessage()]);
}
