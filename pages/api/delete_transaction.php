<?php
// Démarre la session pour accéder aux informations de l'utilisateur connecté (stockées dans $_SESSION)
session_start();

// Charge les fichiers de configuration et de connexion à la base de données
include '../../config/config.php'; // Contient des constantes ou paramètres globaux (ex. chemins, clés)
include '../../config/database.php'; // Initialise la connexion PDO à la base de données via $conn

// Vérifie si l'utilisateur est authentifié en testant l'existence de la clé 'user_id' dans la session
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, renvoie une erreur au format JSON et arrête l'exécution
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

// Récupère les données envoyées dans le corps de la requête (généralement via AJAX, au format JSON)
$input = json_decode(file_get_contents('php://input'), true);
// Extrait l'identifiant de la transaction à supprimer, ou null si non fourni dans les données
$transaction_id = $input['transaction_id'] ?? null;

// Vérifie si l'identifiant de la transaction est présent et non vide
if (empty($transaction_id)) {
    // Si l'ID est manquant, renvoie une erreur JSON et arrête l'exécution
    echo json_encode(['status' => 'error', 'message' => 'ID de la transaction manquant']);
    exit;
}

// Prépare une requête SQL sécurisée pour récupérer les détails de la transaction avant de la supprimer
// - Sélectionne l'ID du compte, le montant et le type (crédit ou débit) de la transaction
// - Filtre par transaction_id et vérifie que le compte associé appartient à l'utilisateur connecté
$stmt = $conn->prepare("SELECT account_id, amount, type FROM transactions WHERE transaction_id = ? AND account_id IN (SELECT account_id FROM accounts WHERE user_id = ?)");
$stmt->execute([$transaction_id, $_SESSION['user_id']]);
// Récupère la transaction sous forme de tableau associatif (ex. ['account_id' => 1, 'amount' => 50, 'type' => 'credit'])
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifie si la transaction existe et appartient bien à l'utilisateur
if (!$transaction) {
    // Si la transaction n'est pas trouvée ou n'appartient pas à l'utilisateur, renvoie une erreur JSON
    echo json_encode(['status' => 'error', 'message' => 'Transaction non trouvée ou non autorisée']);
    exit;
}

// Calcule l'ajustement à appliquer au solde du compte en fonction du type de transaction
// - Si c'est un crédit, on soustrait le montant (annulation d'un ajout)
// - Si c'est un débit, on ajoute le montant (annulation d'un retrait)
$adjustment = ($transaction['type'] === 'credit') ? -$transaction['amount'] : $transaction['amount'];

// Prépare une requête SQL pour mettre à jour le solde du compte associé
// - Ajoute l'ajustement calculé au solde actuel (balance = balance + adjustment)
$stmt = $conn->prepare("UPDATE accounts SET balance = balance + ? WHERE account_id = ?");
$stmt->execute([$adjustment, $transaction['account_id']]);

// Prépare une requête SQL pour supprimer la transaction de la table 'transactions'
$stmt = $conn->prepare("DELETE FROM transactions WHERE transaction_id = ?");
$stmt->execute([$transaction_id]);

// Vérifie si la suppression a affecté au moins une ligne dans la base de données
if ($stmt->rowCount() > 0) {
    // Si la suppression a réussi, renvoie une réponse JSON indiquant le succès
    echo json_encode(['status' => 'success']);
} else {
    // Si aucune ligne n'a été supprimée (erreur ou transaction déjà absente), renvoie une erreur JSON
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression']);
}
