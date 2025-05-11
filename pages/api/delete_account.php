<?php
// Démarre la session pour accéder aux données de l'utilisateur connecté
session_start();

// Charge les fichiers de configuration et de connexion à la base de données
include '../../config/config.php'; // Paramètres globaux (ex. chemins, constantes)
include '../../config/database.php'; // Connexion à la base de données via PDO ($conn)

// Vérifie si l'utilisateur est connecté en testant la présence de 'user_id' dans la session
if (!isset($_SESSION['user_id'])) {
    // Si non connecté, renvoie une erreur JSON et arrête l'exécution
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit;
}

// Récupère l'identifiant de l'utilisateur connecté à partir de la session
$user_id = $_SESSION['user_id'];

// Vérifie que la requête HTTP utilise la méthode POST (souvent via formulaire ou AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données envoyées dans le corps de la requête au format JSON
    $data = json_decode(file_get_contents('php://input'), true);
    // Extrait l'identifiant du compte à supprimer, ou null si non fourni
    $account_id = $data['account_id'] ?? null;

    // Vérifie si l'identifiant du compte est présent et non vide
    if (empty($account_id)) {
        // Si l'ID est manquant, renvoie une erreur JSON et arrête l'exécution
        echo json_encode(['status' => 'error', 'message' => 'ID du compte manquant']);
        exit;
    }

    // Prépare une requête SQL pour vérifier que le compte existe et appartient à l'utilisateur
    $stmt = $conn->prepare("SELECT user_id FROM accounts WHERE account_id = ?");
    $stmt->execute([$account_id]);
    // Récupère les informations du compte sous forme de tableau associatif
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifie si le compte existe et si son propriétaire correspond à l'utilisateur connecté
    if (!$account || $account['user_id'] != $user_id) {
        // Si le compte n'existe pas ou n'appartient pas à l'utilisateur, renvoie une erreur JSON
        echo json_encode(['status' => 'error', 'message' => 'Compte non trouvé ou non autorisé']);
        exit;
    }

    // Prépare une requête SQL pour supprimer le compte de la table 'accounts'
    $stmt = $conn->prepare("DELETE FROM accounts WHERE account_id = ?");
    // Exécute la suppression et vérifie si elle a réussi
    if ($stmt->execute([$account_id])) {
        // Si la suppression réussit, renvoie une réponse JSON de succès
        echo json_encode(['status' => 'success', 'message' => 'Compte supprimé avec succès']);
    } else {
        // Si une erreur SQL survient, renvoie une erreur JSON avec les détails de l'erreur
        echo json_encode(['status' => 'error', 'message' => 'Erreur SQL : ' . implode(', ', $stmt->errorInfo())]);
    }
} else {
    // Si la méthode HTTP n'est pas POST (ex. GET), renvoie une erreur JSON
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
}
