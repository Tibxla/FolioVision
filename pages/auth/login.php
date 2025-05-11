<?php
// Démarre une session pour stocker des données utilisateur (ex. user_id)
session_start();

// Inclut la configuration générale (ex. BASE_URL) et la connexion à la base de données ($conn)
include '../../config/config.php';
include '../../config/database.php';

// Vérifie si l'utilisateur est déjà connecté en cherchant 'user_id' dans la session
if (isset($_SESSION['user_id'])) {
    // Si oui, redirige immédiatement vers le tableau de bord et arrête le script
    header("Location: " . BASE_URL . "pages/user/dashboard.php");
    exit;
}

// Vérifie si le formulaire a été soumis via une requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données saisies dans le formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prépare une requête SQL sécurisée pour trouver l'utilisateur par nom d'utilisateur ou email
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    // Exécute la requête avec le même $username pour les deux champs (nom ou email)
    $stmt->execute([$username, $username]);
    // Récupère les données de l'utilisateur sous forme de tableau associatif
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifie si un utilisateur a été trouvé et si le mot de passe correspond
    if ($user && password_verify($password, $user['password'])) {
        // Si la connexion réussit, stocke les informations dans la session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        // Ajoute les préférences de thème et de couleur de texte de l'utilisateur
        $_SESSION['theme'] = $user['preferred_theme'];
        $_SESSION['text_color'] = $user['preferred_text_color'];
        // Redirige vers le tableau de bord et termine le script
        header("Location: " . BASE_URL . "pages/user/dashboard.php");
        exit;
    } else {
        // Si échec, définit un message d'erreur à afficher
        $error = "Nom d'utilisateur, email ou mot de passe incorrect.";
    }
}
?>

<!-- Début du code HTML pour afficher la page de connexion -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Définit l'encodage pour supporter les caractères spéciaux -->
    <meta charset="UTF-8">
    <!-- Rend la page responsive pour les appareils mobiles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Titre affiché dans l'onglet du navigateur -->
    <title>FolioVision - Connexion</title>
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
<!-- Conteneur principal pour centrer et styliser la boîte de connexion -->
<div class="auth-container">
    <!-- Boîte contenant le formulaire de connexion -->
    <div class="auth-box">
        <!-- Logo cliquable redirigeant vers la page d'accueil -->
        <a href="<?php echo BASE_URL; ?>index.php" class="logo">FolioVision</a>
        <!-- Titre de la section -->
        <h1>Connexion</h1>
        <!-- Affiche un message de succès si l'inscription a réussi -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <p class="success-message">Inscription réussie. Vous pouvez maintenant vous connecter.</p>
        <?php endif; ?>
        <!-- Affiche un message d'erreur si la connexion échoue -->
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <!-- Formulaire de connexion -->
        <form method="post">
            <!-- Étiquette et champ pour le nom d'utilisateur ou email -->
            <label for="username">Nom d'utilisateur ou Email</label>
            <input type="text" id="username" name="username" required>
            <!-- Étiquette et champ pour le mot de passe -->
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
            <!-- Bouton pour soumettre le formulaire -->
            <button type="submit" class="btn">Se connecter</button>
        </form>
        <!-- Lien vers la page d'inscription pour les nouveaux utilisateurs -->
        <p>Pas encore de compte ? <a href="<?php echo BASE_URL; ?>pages/auth/register.php">S'inscrire</a></p>
    </div>
</div>
</body>
</html>