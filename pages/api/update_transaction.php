<?php
// Démarre la session pour accéder aux informations de l'utilisateur connecté (ex. user_id)
session_start();

// Inclut les fichiers nécessaires pour la configuration et la connexion à la base de données
include '../../config/config.php'; // Contient probablement des constantes ou des paramètres globaux
include '../../config/database.php'; // Établit la connexion à la base de données via PDO (variable $conn)

// Vérifie si l'utilisateur est connecté en regardant si 'user_id' existe dans la session
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, renvoie une erreur au format JSON et arrête le script
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

// Récupère l'identifiant de l'utilisateur connecté à partir de la session
$user_id = $_SESSION['user_id'];

// Récupère les données envoyées via la méthode POST (formulaire ou requête AJAX)
$transaction_id = $_POST['transaction_id'] ?? null; // ID de la transaction à modifier
$account_id = $_POST['account_id'] ?? null; // ID du compte associé
$amount = $_POST['amount'] ?? null; // Nouveau montant de la transaction
$type = $_POST['type'] ?? null; // Type de transaction (crédit ou débit)
$main_category_id = $_POST['main_category_id'] ?? null; // Catégorie principale de la transaction
$sub_category_id = $_POST['category_id'] ?? null; // Sous-catégorie (optionnelle)
$transaction_date = $_POST['transaction_date'] ?? null; // Date de la transaction
$payment_method = $_POST['payment_method'] ?? null; // Méthode de paiement utilisée
$description = $_POST['description'] ?? ''; // Description facultative de la transaction

// Vérifie que tous les champs obligatoires sont remplis (non vides)
if (empty($transaction_id) || empty($account_id) || empty($amount) || empty($type) || empty($transaction_date) || empty($payment_method) || empty($main_category_id)) {
    // Si un champ obligatoire manque, renvoie une erreur JSON et arrête le script
    echo json_encode(['status' => 'error', 'message' => 'Champs obligatoires manquants']);
    exit;
}

// Définit la catégorie finale : utilise la sous-catégorie si elle existe, sinon la catégorie principale
$category_id = !empty($sub_category_id) ? $sub_category_id : $main_category_id;

// Récupère les anciennes données de la transaction pour calculer l'ajustement du solde
$stmt = $conn->prepare("SELECT amount, type FROM transactions WHERE transaction_id = ? AND account_id = ?");
$stmt->execute([$transaction_id, $account_id]); // Exécute la requête avec les ID fournis
$old_transaction = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère les données sous forme de tableau associatif

// Vérifie si la transaction existe et appartient bien au compte spécifié
if (!$old_transaction) {
    // Si la transaction n'est pas trouvée, renvoie une erreur JSON et arrête le script
    echo json_encode(['status' => 'error', 'message' => 'Transaction non trouvée ou non autorisée']);
    exit;
}

// Récupère les anciennes valeurs pour le calcul du solde
$old_amount = $old_transaction['amount']; // Ancien montant
$old_type = $old_transaction['type']; // Ancien type (crédit ou débit)
$new_amount = $amount; // Nouveau montant
$new_type = $type; // Nouveau type

// Calcule l'ajustement à appliquer au solde du compte
$adjustment = 0;
// Étape 1 : Annule l'effet de l'ancienne transaction
if ($old_type === 'credit') {
    $adjustment -= $old_amount; // Retire l'ancien crédit du solde
} else {
    $adjustment += $old_amount; // Annule l'ancien débit (ajoute le montant au solde)
}
// Étape 2 : Applique l'effet de la nouvelle transaction
if ($new_type === 'credit') {
    $adjustment += $new_amount; // Ajoute le nouveau crédit au solde
} else {
    $adjustment -= $new_amount; // Soustrait le nouveau débit du solde
}

// Met à jour le solde du compte dans la table 'accounts'
$stmt = $conn->prepare("UPDATE accounts SET balance = balance + ? WHERE account_id = ? AND user_id = ?");
$stmt->execute([$adjustment, $account_id, $user_id]); // Applique l'ajustement au solde

// Met à jour les détails de la transaction dans la table 'transactions'
$stmt = $conn->prepare("
    UPDATE transactions 
    SET amount = ?, type = ?, category_id = ?, transaction_date = ?, payment_method = ?, description = ?, value_date = ?
    WHERE transaction_id = ? AND account_id = ?
");
$stmt->execute([$amount, $type, $category_id, $transaction_date, $payment_method, $description, $transaction_date, $transaction_id, $account_id]);

// Renvoie une réponse JSON pour indiquer que la mise à jour a réussi
echo json_encode(['status' => 'success']);
