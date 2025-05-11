<?php
// Démarre une session pour gérer les variables de session (ex. user_id)
session_start();

// Inclut la configuration générale (ex. BASE_URL) et la connexion à la base de données ($conn)
include '../../config/config.php';
include '../../config/database.php';

// Vérifie si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    // Si oui, redirige vers le tableau de bord et arrête le script
    header("Location: " . BASE_URL . "pages/user/dashboard.php");
    exit;
}

// Vérifie si le formulaire a été soumis via une requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données du formulaire
    $username = $_POST['username'];
    $email = $_POST['email'];
    // Hache le mot de passe pour le stocker de manière sécurisée dans la base de données
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Vérifie si le nom d'utilisateur ou l'email existe déjà dans la table 'users'
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si un utilisateur avec ce nom ou email existe déjà
    if ($existing_user) {
        // Définit un message d'erreur
        $error = "Le nom d'utilisateur ou l'email est déjà utilisé.";
    } else {
        // Prépare une requête pour insérer un nouvel utilisateur dans la base de données
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        // Exécute la requête avec les données fournies
        if ($stmt->execute([$username, $email, $password])) {
            // Redirige vers la page de connexion après inscription réussie
            header("Location: " . BASE_URL . "pages/auth/login.php?success=1");
            exit;
        } else {
            // Si échec, affiche un message d'erreur
            $error = "Une erreur est survenue lors de l'inscription.";
        }
    }
}
?>

<!-- Début du code HTML pour afficher la page d'inscription -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Définit l'encodage des caractères -->
    <meta charset="UTF-8">
    <!-- Assure une mise en page adaptée aux appareils mobiles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Titre de la page dans l'onglet du navigateur -->
    <title>FolioVision - Inscription</title>
    <!-- Lien vers le fichier CSS pour styliser la page -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <!-- Importe des polices Google pour une typographie personnalisée -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- Définit une série d’icônes pour les appareils Apple (iPhone, iPad) dans différentes résolutions -->
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo BASE_URL; ?>assets/img/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo BASE_URL; ?>assets/img/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo BASE_URL; ?>assets/img/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo BASE_URL; ?>assets/img/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo BASE_URL; ?>assets/img/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo BASE_URL; ?>assets/img/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo BASE_URL; ?>assets/img/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo BASE_URL; ?>assets/img/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo BASE_URL; ?>assets/img/apple-icon-180x180.png">
    <!-- Définit des favicons pour les navigateurs et appareils Android dans différentes tailles -->
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo BASE_URL; ?>assets/img/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo BASE_URL; ?>assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo BASE_URL; ?>assets/img/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo BASE_URL; ?>assets/img/favicon-16x16.png">
    <!-- Lien vers un fichier manifeste pour activer des fonctionnalités d’application web progressive (PWA) -->
    <link rel="manifest" href="<?php echo BASE_URL; ?>assets/img/manifest.json">
    <!-- Configuration spécifique pour les tuiles sous Windows -->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo BASE_URL; ?>assets/img/ms-icon-144x144.png">
    <!-- Définit la couleur de la barre de navigation sur mobile (ex. barre d’adresse sur Chrome Android) -->
    <meta name="theme-color" content="#ffffff">
</head>
<body>
<!-- Conteneur principal pour centrer et styliser la boîte d'inscription -->
<div class="auth-container">
    <!-- Boîte contenant le formulaire d'inscription -->
    <div class="auth-box">
        <!-- Logo cliquable redirigeant vers la page d'accueil -->
        <a href="<?php echo BASE_URL; ?>index.php" class="logo">FolioVision</a>
        <!-- Titre de la section -->
        <h1>Inscription</h1>
        <!-- Affiche un message d'erreur ou de succès selon le résultat -->
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <!-- Formulaire d'inscription -->
        <form method="post">
            <!-- Étiquette et champ pour le nom d'utilisateur -->
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required>
            <!-- Étiquette et champ pour l'email -->
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <!-- Étiquette et champ pour le mot de passe -->
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
            <!-- Bouton pour soumettre le formulaire -->
            <button type="submit" class="btn">S'inscrire</button>
        </form>
        <!-- Lien vers la page de connexion pour les utilisateurs existants -->
        <p>Déjà un compte ? <a href="<?php echo BASE_URL; ?>pages/auth/login.php">Se connecter</a></p>
    </div>
</div>
</body>
</html>