<?php
session_start();
include '../../config/config.php';
include '../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

$user_id = $_SESSION['user_id'];
$transaction_id = $_GET['transaction_id'] ?? null;

if (empty($transaction_id)) {
    echo json_encode(['status' => 'error', 'message' => 'ID de transaction manquant']);
    exit;
}

$stmt = $conn->prepare("
    SELECT t.*, a.currency,
           IF(c.parent_id IS NULL, c.name, p.name) AS category_name,
           IF(c.parent_id IS NOT NULL, c.name, NULL) AS sub_category_name,
           c.category_id AS category_id,
           p.category_id AS parent_category_id
    FROM transactions t
    JOIN accounts a ON t.account_id = a.account_id
    LEFT JOIN categories c ON t.category_id = c.category_id
    LEFT JOIN categories p ON c.parent_id = p.category_id
    WHERE t.transaction_id = ? AND a.user_id = ?
");
$stmt->execute([$transaction_id, $user_id]);
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

if ($transaction) {
    echo json_encode(['status' => 'success', 'transaction' => $transaction]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Transaction non trouvée']);
}
?>