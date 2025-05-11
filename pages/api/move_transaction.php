<?php
// Démarre la session pour accéder aux données de l'utilisateur connecté, comme son identifiant
session_start();

// Inclut les fichiers nécessaires : configuration générale et connexion à la base de données
include '../../config/config.php'; // Contient des constantes comme BASE_URL ou des paramètres globaux
include '../../config/database.php'; // Définit la connexion PDO à la base de données via la variable $conn

// Vérifie si l'utilisateur est authentifié en cherchant 'user_id' dans la session
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, renvoie une réponse JSON indiquant une erreur et stoppe l'exécution
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

// Récupère l'identifiant de l'utilisateur connecté depuis la session
$user_id = $_SESSION['user_id'];

// Récupère les données envoyées par une requête (souvent AJAX) au format JSON via l'entrée standard
$input = json_decode(file_get_contents('php://input'), true);
// Extrait l'ID de la transaction à déplacer, ou null si non fourni
$transaction_id = $input['transaction_id'] ?? null;
// Extrait la liste des IDs des comptes cibles, ou un tableau vide si non fourni
$target_account_ids = $input['target_account_ids'] ?? [];

// Vérifie que l'ID de la transaction et au moins un compte cible sont bien fournis
if (empty($transaction_id) || empty($target_account_ids)) {
    // Si une de ces données est absente, renvoie une erreur JSON et stoppe l'exécution
    echo json_encode(['status' => 'error', 'message' => 'Données manquantes']);
    exit;
}

// Prépare une requête SQL pour récupérer les détails de la transaction d'origine, incluant la devise et l'ID du compte d'origine
$stmt = $conn->prepare("
    SELECT t.*, a.currency, a.account_id AS origin_account_id
    FROM transactions t
    JOIN accounts a ON t.account_id = a.account_id
    WHERE t.transaction_id = ? AND a.user_id = ?
");
// Exécute la requête avec l'ID de la transaction et l'ID de l'utilisateur pour garantir qu'elle appartient à cet utilisateur
$stmt->execute([$transaction_id, $user_id]);
// Récupère les données de la transaction sous forme de tableau associatif
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifie si la transaction existe et appartient bien à l'utilisateur
if (!$transaction) {
    // Si aucune transaction n'est trouvée, renvoie une erreur JSON et stoppe l'exécution
    echo json_encode(['status' => 'error', 'message' => 'Transaction non trouvée']);
    exit;
}

// Prépare une requête pour vérifier que les comptes cibles sont valides (appartiennent à l'utilisateur et ne sont pas le compte d'origine)
$stmt = $conn->prepare("SELECT account_id FROM accounts WHERE account_id IN (" . implode(',', array_fill(0, count($target_account_ids), '?')) . ") AND user_id = ? AND account_id != ?");
// Génère dynamiquement les placeholders (?) pour chaque ID de compte cible dans la clause IN
// Exécute la requête avec les IDs des comptes cibles, l'ID utilisateur et l'ID du compte d'origine à exclure
$stmt->execute(array_merge($target_account_ids, [$user_id, $transaction['origin_account_id']]));
// Récupère la liste des IDs de comptes valides sous forme de tableau unidimensionnel
$valid_account_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Vérifie que le nombre de comptes cibles valides correspond au nombre initial (tous doivent être valides)
if (count($valid_account_ids) !== count($target_account_ids)) {
    // Si un compte cible est invalide ou identique au compte d'origine, renvoie une erreur
    echo json_encode(['status' => 'error', 'message' => 'Comptes invalides ou identiques au compte d\'origine']);
    exit;
}

// Démarre une transaction SQL pour assurer que toutes les opérations suivantes réussissent ou échouent ensemble
try {
    $conn->beginTransaction(); // Ouvre une transaction pour garantir l'intégrité des données

    // Supprime la transaction d'origine de la table 'transactions'
    $stmt = $conn->prepare("DELETE FROM transactions WHERE transaction_id = ?");
    $stmt->execute([$transaction_id]);

    // Calcule l'ajustement du solde du compte d'origine en fonction du type de transaction
    $origin_adjustment = ($transaction['type'] === 'credit') ? -$transaction['amount'] : $transaction['amount'];
    // Si c'est un crédit, on soustrait le montant (car la transaction est supprimée) ; si c'est un débit, on l'ajoute
    $stmt = $conn->prepare("UPDATE accounts SET balance = balance + ? WHERE account_id = ?");
    $stmt->execute([$origin_adjustment, $transaction['origin_account_id']]);

    // Parcourt chaque compte cible valide pour y ajouter la transaction
    foreach ($valid_account_ids as $target_account_id) {
        // Prépare une insertion pour répliquer la transaction dans le compte cible
        $stmt = $conn->prepare("
            INSERT INTO transactions (account_id, category_id, amount, transaction_date, value_date, type, payment_method, description)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        // Insère les mêmes détails que la transaction d'origine, mais avec le nouvel account_id
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

        // Calcule l'ajustement du solde du compte cible en fonction du type de transaction
        $target_adjustment = ($transaction['type'] === 'credit') ? $transaction['amount'] : -$transaction['amount'];
        // Si c'est un crédit, on ajoute le montant ; si c'est un débit, on le soustrait
        $stmt = $conn->prepare("UPDATE accounts SET balance = balance + ? WHERE account_id = ?");
        $stmt->execute([$target_adjustment, $target_account_id]);
    }

    // Valide toutes les opérations si aucune erreur n'est survenue
    $conn->commit();
    // Renvoie une réponse JSON indiquant le succès de l'opération
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    // En cas d'erreur (ex. problème de base de données), annule toutes les modifications
    $conn->rollBack();
    // Renvoie une erreur JSON avec le message de l'exception
    echo json_encode(['status' => 'error', 'message' => 'Erreur : ' . $e->getMessage()]);
}
