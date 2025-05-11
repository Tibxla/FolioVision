<?php
// Démarre la session pour accéder aux variables de session, notamment l'identifiant de l'utilisateur
session_start();

// Inclut les fichiers de configuration et de connexion à la base de données
include '../../config/config.php'; // Contient des constantes ou paramètres globaux (ex. chemins, clés)
include '../../config/database.php'; // Fournit l'objet PDO $conn pour interagir avec la base de données

// Vérifie si l'utilisateur est connecté en testant la présence de 'user_id' dans la session
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, renvoie une réponse JSON avec un statut d'erreur et un message
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    // Arrête l'exécution du script
    exit;
}

// Récupère l'identifiant de l'utilisateur connecté depuis la variable de session
$user_id = $_SESSION['user_id'];

// Prépare une requête SQL sécurisée avec un paramètre pour éviter les injections SQL
// - Sélectionne l'ID et le nom des comptes dans la table 'accounts'
// - Filtre les résultats pour ne récupérer que les comptes liés à l'utilisateur connecté (via user_id)
$stmt = $conn->prepare("SELECT account_id, account_name FROM accounts WHERE user_id = ?");
// Exécute la requête en passant l'identifiant de l'utilisateur comme paramètre
$stmt->execute([$user_id]);
// Récupère tous les résultats sous forme de tableau associatif (clés = noms des colonnes : account_id, account_name)
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Renvoie une réponse au format JSON avec un statut de succès et la liste des comptes récupérés
echo json_encode(['status' => 'success', 'accounts' => $accounts]);
