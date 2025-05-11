<?php
// Démarre la session pour accéder aux données de l'utilisateur connecté
session_start();

// Inclut les fichiers de configuration et de connexion à la base de données
include '../../config/config.php'; // Paramètres globaux
include '../../config/database.php'; // Connexion PDO ($conn)

// Vérifie si l'utilisateur est connecté via la présence de 'user_id' dans la session
if (!isset($_SESSION['user_id'])) {
    // Si non connecté, renvoie une erreur JSON et arrête le script
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit;
}

// Récupère l'identifiant de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Vérifie que la requête est bien une soumission de formulaire via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les nouvelles valeurs envoyées par le formulaire
    $new_username = $_POST['username']; // Nouveau nom d'utilisateur
    $new_email = $_POST['email']; // Nouvel email

    // Vérifie si le nom d'utilisateur est déjà utilisé par un autre utilisateur
    $stmt_username = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
    $stmt_username->execute([$new_username, $user_id]); // Exclut l'utilisateur actuel
    $existing_username = $stmt_username->fetch(PDO::FETCH_ASSOC); // Retourne un résultat si trouvé

    // Vérifie si l'email est déjà utilisé par un autre utilisateur
    $stmt_email = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
    $stmt_email->execute([$new_email, $user_id]); // Exclut l'utilisateur actuel
    $existing_email = $stmt_email->fetch(PDO::FETCH_ASSOC); // Retourne un résultat si trouvé

    // Génère un message d'erreur selon les conflits détectés
    if ($existing_username && $existing_email) {
        // Si les deux sont déjà pris
        echo json_encode(['status' => 'error', 'message' => 'Le nom d\'utilisateur et l\'email sont déjà utilisés.']);
    } elseif ($existing_username) {
        // Si seul le nom d'utilisateur est pris
        echo json_encode(['status' => 'error', 'message' => 'Le nom d\'utilisateur est déjà utilisé.']);
    } elseif ($existing_email) {
        // Si seul l'email est pris
        echo json_encode(['status' => 'error', 'message' => 'L\'email est déjà utilisé.']);
    } else {
        // Si aucune duplication, met à jour les informations dans la table 'users'
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
        if ($stmt->execute([$new_username, $new_email, $user_id])) {
            // Si la mise à jour réussit, met à jour la session avec le nouveau nom d'utilisateur
            $_SESSION['username'] = $new_username;
            // Renvoie une réponse JSON avec les nouvelles données
            echo json_encode([
                'status' => 'success',
                'message' => 'Informations mises à jour avec succès',
                'username' => $new_username,
                'email' => $new_email
            ]);
        } else {
            // Si la mise à jour échoue (erreur SQL), renvoie une erreur
            echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour']);
        }
    }
} else {
    // Si la méthode n'est pas POST, renvoie une erreur
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
}
