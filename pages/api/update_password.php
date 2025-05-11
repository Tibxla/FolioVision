<?php
// Démarre la session pour accéder aux informations de l'utilisateur
session_start();

// Inclut les fichiers de configuration et de connexion à la base de données
include '../../config/config.php'; // Paramètres globaux
include '../../config/database.php'; // Connexion PDO ($conn)

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si non connecté, renvoie une erreur JSON et arrête le script
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit;
}

// Récupère l'identifiant de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Vérifie que la requête utilise la méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les mots de passe envoyés via le formulaire
    $current_password = $_POST['current_password']; // Mot de passe actuel
    $new_password = $_POST['new_password']; // Nouveau mot de passe
    $confirm_password = $_POST['confirm_password']; // Confirmation du nouveau mot de passe

    // Récupère le mot de passe actuel haché de l'utilisateur dans la base de données
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère le mot de passe haché

    // Vérifie si le mot de passe actuel fourni correspond au mot de passe haché
    if (!password_verify($current_password, $user['password'])) {
        // Si incorrect, renvoie une erreur
        echo json_encode(['status' => 'error', 'message' => 'Le mot de passe actuel est incorrect.']);
    }
    // Vérifie si le nouveau mot de passe correspond à sa confirmation
    elseif ($new_password !== $confirm_password) {
        // Si différent, renvoie une erreur
        echo json_encode(['status' => 'error', 'message' => 'Le nouveau mot de passe et la confirmation ne correspondent pas.']);
    } else {
        // Hache le nouveau mot de passe pour le stocker de manière sécurisée
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        // Met à jour le mot de passe dans la table 'users'
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        if ($stmt->execute([$hashed_password, $user_id])) {
            // Si la mise à jour réussit, renvoie un message de succès
            echo json_encode(['status' => 'success', 'message' => 'Mot de passe mis à jour avec succès']);
        } else {
            // Si la mise à jour échoue, renvoie une erreur
            echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour']);
        }
    }
} else {
    // Si la méthode n'est pas POST, renvoie une erreur
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
}
