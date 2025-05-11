<?php
session_start();
include '../../config/config.php';
include '../../config/database.php';

// Désactiver l'affichage des erreurs pour éviter les sorties HTML inattendues
error_reporting(0);
ini_set('display_errors', 0);

// Vérification de la session utilisateur
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['investment_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'ID de l\'investissement manquant']);
    exit;
}

try {
    // Préparation de la requête SQL pour supprimer l'investissement
    $stmt = $conn->prepare("DELETE FROM investments WHERE investment_id = :investment_id AND user_id = :user_id");
    $stmt->execute([
        'investment_id' => $data['investment_id'],
        'user_id' => $user_id
    ]);
    if ($stmt->rowCount() > 0) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Investissement supprimé']);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Investissement non trouvé ou non autorisé']);
    }
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Erreur inattendue : ' . $e->getMessage()]);
}

// Ajout d'un log pour vérifier la sortie
error_log('delete_investment.php: Fin du script');
?>