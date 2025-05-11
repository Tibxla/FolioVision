<?php
// Démarre la session pour gérer les variables comme 'user_id', 'theme', et 'text_color'
session_start();

// Inclut les fichiers de configuration générale (ex. BASE_URL) et de connexion à la base de données ($conn)
include '../../config/config.php';
include '../../config/database.php';

// Récupère le thème et la couleur du texte depuis la session, avec des valeurs par défaut si non définies
$theme = $_SESSION['theme'] ?? 'dark'; // Thème par défaut : sombre
$text_color = $_SESSION['text_color'] ?? '#00bcd4'; // Couleur par défaut : cyan

// Vérifie si l'utilisateur est connecté en regardant si 'user_id' existe dans la session
if (!isset($_SESSION['user_id'])) {
    // Si non connecté, redirige vers la page de connexion et arrête l'exécution du script
    header("Location: " . BASE_URL . "pages/auth/login.php");
    exit;
}

// Prépare une requête SQL pour récupérer le nom d'utilisateur et l'email depuis la table 'users'
$stmt = $conn->prepare("SELECT username, email FROM users WHERE user_id = ?");
// Exécute la requête avec l'ID de l'utilisateur stocké dans la session
$stmt->execute([$_SESSION['user_id']]);
// Récupère les données sous forme de tableau associatif (ex. ['username' => 'john', 'email' => 'john@example.com'])
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!-- Début du code HTML pour la structure de la page -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Définit l'encodage des caractères pour supporter les accents et caractères spéciaux -->
    <meta charset="UTF-8">
    <!-- Assure une mise en page adaptée aux appareils mobiles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Titre de la page affiché dans l'onglet du navigateur -->
    <title>FolioVision - Profil</title>
    <!-- Charge le fichier CSS principal pour le style de la page -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <!-- Importe les polices Google Orbitron et Roboto pour une typographie moderne -->
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
    <style>
        :root {
            /* Définit des variables CSS globales pour personnaliser le thème */
        <?php if ($theme === 'light'): ?>
            /* Si le thème est clair */
            --background-color: #ffffff; /* Fond blanc */
            --container-background: #f5f5f5; /* Fond des conteneurs gris clair */
            --secondary-container-background: #e0e0e0; /* Fond secondaire gris */
            --text-color: #333333; /* Texte sombre */
            --light-text-color: #000000; /* Texte noir pour contraste */
        <?php else: ?>
            /* Si le thème est sombre (par défaut) */
            --background-color: #121212; /* Fond très sombre */
            --container-background: #1a1a1a; /* Fond des conteneurs gris foncé */
            --secondary-container-background: #242424; /* Fond secondaire gris moyen */
            --text-color: #e0e0e0; /* Texte clair */
            --light-text-color: #ffffff; /* Texte blanc pour contraste */
        <?php endif; ?>
            /* Couleur d'accent personnalisée définie par l'utilisateur */
            --accent-color: <?php echo htmlspecialchars($text_color); ?>;
        }
    </style>
</head>
<body>
<!-- Conteneur principal de la page de profil -->
<div class="profile-container">
    <h1>Modifier votre profil</h1> <!-- Titre principal -->

    <!-- Formulaire pour modifier le nom d'utilisateur et l'email -->
    <form id="profile-info-form" method="post">
        <h2>Informations personnelles</h2>
        <label for="username">Nom d'utilisateur</label>
        <!-- Affiche le nom d'utilisateur actuel, protégé contre les attaques XSS avec htmlspecialchars -->
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <label for="email">Email</label>
        <!-- Affiche l'email actuel, également protégé avec htmlspecialchars -->
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <!-- Bouton pour soumettre les modifications -->
        <button type="submit" class="btn">Mettre à jour les informations</button>
    </form>

    <!-- Formulaire pour modifier le mot de passe -->
    <form id="password-form" method="post">
        <h2>Modifier le mot de passe</h2>
        <label for="current_password">Mot de passe actuel</label>
        <input type="password" id="current_password" name="current_password" required>
        <label for="new_password">Nouveau mot de passe</label>
        <input type="password" id="new_password" name="new_password" required>
        <label for="confirm_password">Confirmer le nouveau mot de passe</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <!-- Bouton pour soumettre le changement de mot de passe -->
        <button type="submit" class="btn">Mettre à jour le mot de passe</button>
    </form>

    <!-- Lien pour retourner au tableau de bord -->
    <p><a href="<?php echo BASE_URL; ?>pages/user/dashboard.php" class="btn-secondary">Retour au tableau de bord</a></p>
</div>

<!-- Charge un fichier JavaScript pour gérer les interactions dynamiques (ex. soumission AJAX des formulaires) -->
<script src="<?php echo BASE_URL; ?>assets/js/script.js"></script>
</body>
</html>