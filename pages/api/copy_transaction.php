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

// Récupère les données envoyées dans le corps de la requête au format JSON
$input = json_decode(file_get_contents('php://input'), true);
$transaction_id = $input['transaction_id'] ?? null; // ID de la transaction à copier
$target_account_ids = $input['target_account_ids'] ?? []; // Liste des ID des comptes cibles

// Vérifie que l'ID de la transaction et au moins un compte cible sont fournis
if (empty($transaction_id) || empty($target_account_ids)) {
    // Si une donnée essentielle manque, renvoie une erreur JSON et arrête l'exécution
    echo json_encode(['status' => 'error', 'message' => 'Données manquantes']);
    exit;
}

// Prépare une requête SQL pour récupérer les détails de la transaction d'origine
// - Joint la table 'transactions' avec 'accounts' pour obtenir la devise du compte
// - Filtre par transaction_id et vérifie que le compte appartient à l'utilisateur
$stmt = $conn->prepare("
    SELECT t.*, a.currency
    FROM transactions t
    JOIN accounts a ON t.account_id = a.account_id
    WHERE t.transaction_id = ? AND a.user_id = ?
");
$stmt->execute([$transaction_id, $user_id]);
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifie si la transaction existe et appartient à l'utilisateur
if (!$transaction) {
    // Si la transaction n'est pas trouvée, renvoie une erreur JSON
    echo json_encode(['status' => 'error', 'message' => 'Transaction non trouvée']);
    exit;
}

// Prépare une requête SQL pour vérifier que les comptes cibles sont valides
// - Utilise un tableau dynamique de placeholders (?) basé sur le nombre de comptes cibles
$stmt = $conn->prepare("SELECT account_id FROM accounts WHERE account_id IN (" . implode(',', array_fill(0, count($target_account_ids), '?')) . ") AND user_id = ?");
// Combine les ID des comptes cibles avec l'ID de l'utilisateur pour l'exécution
$stmt->execute(array_merge($target_account_ids, [$user_id]));
// Récupère la liste des comptes valides sous forme de tableau simple (colonne account_id)
$valid_account_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Vérifie que tous les comptes cibles fournis sont valides et appartiennent à l'utilisateur
if (count($valid_account_ids) !== count($target_account_ids)) {
    // Si un compte cible est invalide ou non autorisé, renvoie une erreur JSON
    echo json_encode(['status' => 'error', 'message' => 'Comptes invalides']);
    exit;
}

// Bloc try-catch pour gérer les erreurs lors des opérations sur la base de données
try {
    // Démarre une transaction SQL pour garantir que toutes les opérations réussissent ou échouent ensemble
    $conn->beginTransaction();
    // Parcourt chaque compte cible valide pour y dupliquer la transaction
    foreach ($valid_account_ids as $target_account_id) {
        // Prépare une requête SQL pour insérer une nouvelle transaction dans le compte cible
        $stmt = $conn->prepare("
            INSERT INTO transactions (account_id, category_id, amount, transaction_date, value_date, type, payment_method, description)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        // Insère les données de la transaction d'origine dans le nouveau compte
        $stmt->execute([
            $target_account_id,
            $transaction['category_id'],
            $transaction['amount'],
            $transaction['transaction_date'],
            $transaction['value_date'],
            $transaction['type'],
            $transaction['payment_method'],
            $transaction['description']
        ]);

        // Calcule l'ajustement du solde pour le compte cible
        // - Si crédit, ajoute le montant ; si débit, soustrait le montant
        $adjustment = ($transaction['type'] === 'credit') ? $transaction['amount'] : -$transaction['amount'];
        // Prépare et exécute une requête pour mettre à jour le solde du compte cible
        $stmt = $conn->prepare("UPDATE accounts SET balance = balance + ? WHERE account_id = ?");
        $stmt->execute([$adjustment, $target_account_id]);
    }
    // Si toutes les opérations réussissent, valide la transaction SQL
    $conn->commit();
    // Renvoie une réponse JSON indiquant le succès
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    // En cas d'erreur, annule toutes les modifications effectuées dans la transaction
    $conn->rollBack();
    // Renvoie une erreur JSON avec le message de l'exception
    echo json_encode(['status' => 'error', 'message' => 'Erreur : ' . $e->getMessage()]);
}
