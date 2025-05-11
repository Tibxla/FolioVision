<?php
session_start();
include '../../config/config.php';
include '../../config/database.php';

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer le corps de la requête JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);
$goal_id = $data['goal_id'] ?? '';

if (empty($goal_id)) {
    echo json_encode(['status' => 'error', 'message' => 'ID du projet manquant']);
    exit;
}

try {
    // Vérifier si le projet appartient à l'utilisateur
    $stmt = $conn->prepare("SELECT * FROM goals WHERE goal_id = :goal_id AND user_id = :user_id");
    $stmt->execute(['goal_id' => $goal_id, 'user_id' => $user_id]);
    $goal = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$goal) {
        echo json_encode(['status' => 'error', 'message' => 'Projet non trouvé ou non autorisé']);
        exit;
    }

    // Supprimer le projet
    $stmt = $conn->prepare("DELETE FROM goals WHERE goal_id = :goal_id");
    $stmt->execute(['goal_id' => $goal_id]);

    echo json_encode(['status' => 'success', 'message' => 'Projet supprimé avec succès']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
}
?>