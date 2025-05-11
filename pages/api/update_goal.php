<?php
session_start();
include '../../config/config.php';
include '../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['user_id'];
$goal_id = $_POST['goal_id'] ?? '';
$action = $_POST['action'] ?? '';

if (empty($goal_id)) {
    echo json_encode(['status' => 'error', 'message' => 'ID du projet manquant']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT * FROM goals WHERE goal_id = :goal_id AND user_id = :user_id");
    $stmt->execute(['goal_id' => $goal_id, 'user_id' => $user_id]);
    $goal = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$goal) {
        echo json_encode(['status' => 'error', 'message' => 'Projet non trouvé ou non autorisé']);
        exit;
    }

    if ($action === 'add' || $action === 'remove') {
        $amount = $_POST['amount'] ?? '';
        if (empty($amount) || !is_numeric($amount) || $amount <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Montant invalide']);
            exit;
        }

        $current_amount = $goal['current_amount'];
        if ($action === 'add') {
            $new_amount = $current_amount + $amount;
        } else {
            $new_amount = $current_amount - $amount;
            if ($new_amount < 0) {
                echo json_encode(['status' => 'error', 'message' => 'Le montant actuel ne peut pas être négatif']);
                exit;
            }
        }

        $stmt = $conn->prepare("UPDATE goals SET current_amount = :current_amount WHERE goal_id = :goal_id");
        $stmt->execute([
            'current_amount' => $new_amount,
            'goal_id' => $goal_id
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Montant mis à jour avec succès']);
    } else {
        $goal_name = $_POST['goal_name'] ?? '';
        $target_amount = $_POST['target_amount'] ?? '';
        $currency = $_POST['currency'] ?? '';
        $current_amount = $_POST['current_amount'] ?? '';
        $due_date = $_POST['due_date'] ?? '';
        $comment = $_POST['comment'] ?? '';

        if (empty($goal_name) || empty($target_amount) || empty($currency) || empty($current_amount)) {
            echo json_encode(['status' => 'error', 'message' => 'Données manquantes pour la mise à jour']);
            exit;
        }

        if (!is_numeric($target_amount) || $target_amount <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Le montant cible doit être un nombre positif']);
            exit;
        }

        if (!is_numeric($current_amount) || $current_amount < 0) {
            echo json_encode(['status' => 'error', 'message' => 'Le montant actuel doit être un nombre non négatif']);
            exit;
        }

        $valid_currencies = ['EUR', 'USD'];
        if (!in_array($currency, $valid_currencies)) {
            echo json_encode(['status' => 'error', 'message' => 'Devise invalide']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE goals SET goal_name = :goal_name, target_amount = :target_amount, currency = :currency, current_amount = :current_amount, due_date = :due_date, comment = :comment WHERE goal_id = :goal_id");
        $stmt->execute([
            'goal_name' => $goal_name,
            'target_amount' => $target_amount,
            'currency' => $currency,
            'current_amount' => $current_amount,
            'due_date' => $due_date ? $due_date : null,
            'comment' => $comment,
            'goal_id' => $goal_id
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Projet mis à jour avec succès']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
}
?>