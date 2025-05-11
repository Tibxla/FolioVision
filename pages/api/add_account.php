<?php
// Déclare $conn comme globale pour y accéder (initialisée dans database.php)
global $conn;

// Démarre la session pour vérifier l'authentification de l'utilisateur
session_start();

// Charge les fichiers de configuration et de connexion à la base de données
include '../../config/config.php'; // Contient des constantes comme BASE_URL
include '../../config/database.php'; // Fournit l'objet PDO $conn

// Désactive l'affichage des erreurs à l'écran pour ne pas perturber les réponses JSON
ini_set('display_errors', 0);
error_reporting(E_ALL); // Active la détection de toutes les erreurs
ini_set('log_errors', 1); // Journalise les erreurs dans un fichier
ini_set('error_log', '../../error.log'); // Chemin du fichier de log (doit être valide)

// Définit l'en-tête HTTP pour indiquer que la réponse est en JSON
header('Content-Type: application/json');

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si non, renvoie une erreur JSON et arrête le script
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit;
}

// Récupère l'ID de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Vérifie que la requête utilise la méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données du formulaire
    $account_name = $_POST['account_name']; // Nom du compte
    $account_type = $_POST['account_type']; // Type de compte (ex. bank, cash)
    $bank_subtype = $_POST['bank_subtype'] ?? null; // Sous-type pour comptes bancaires (ex. checking, savings)
    $balance = $_POST['balance']; // Solde initial
    $currency = $_POST['currency']; // Devise (ex. EUR, USD)

    // Gère le sous-type pour les comptes bancaires
    if ($account_type === 'bank') {
        $bank_subtype = $_POST['bank_subtype'] ?? null; // Récupère à nouveau (redondant)
        if (empty($bank_subtype)) {
            // Si le sous-type manque pour un compte bancaire, renvoie une erreur
            echo json_encode(['status' => 'error', 'message' => 'Le sous-type est requis pour les comptes bancaires']);
            exit;
        }
    } else {
        $bank_subtype = null; // Force à NULL pour les autres types de comptes
    }

    // Valide les champs obligatoires côté serveur
    if (empty($account_name) || empty($account_type) || !isset($balance) || $balance === '' || empty($currency)) {
        // Si un champ manque, renvoie une erreur
        echo json_encode(['status' => 'error', 'message' => 'Tous les champs sont requis']);
        exit;
    }
    if ($account_type === 'bank' && empty($bank_subtype)) {
        // Vérifie à nouveau le sous-type (redondant avec le bloc précédent)
        echo json_encode(['status' => 'error', 'message' => 'Le sous-type est requis pour les comptes bancaires']);
        exit;
    }
    if (!is_numeric($balance)) {
        // Vérifie que le solde est un nombre
        echo json_encode(['status' => 'error', 'message' => 'Le solde doit être un nombre']);
        exit;
    }

    // Tente d’insérer le compte dans la base de données
    try {
        // Prépare une requête SQL pour insérer les données dans la table 'accounts'
        $stmt = $conn->prepare("INSERT INTO accounts (user_id, account_name, account_type, bank_subtype, balance, currency) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $account_name, $account_type, $bank_subtype, $balance, $currency])) {
            // Si l'insertion réussit, récupère l'ID du compte créé
            $account_id = $conn->lastInsertId();
            // Renvoie une réponse JSON avec les détails du compte
            echo json_encode([
                'status' => 'success',
                'account_id' => $account_id,
                'account_name' => $account_name,
                'balance' => $balance,
                'currency' => $currency
            ]);
        } else {
            // Si l'insertion échoue, renvoie une erreur générique
            echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'insertion dans la base de données']);
        }
    } catch (PDOException $e) {
        // En cas d’erreur SQL (ex. problème de connexion ou contrainte), renvoie l’erreur détaillée
        echo json_encode(['status' => 'error', 'message' => 'Erreur SQL : ' . $e->getMessage()]);
    }
} else {
    // Si la méthode HTTP n’est pas POST, renvoie une erreur
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
}
