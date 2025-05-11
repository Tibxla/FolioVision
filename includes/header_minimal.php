<?php
// Vérifie si une session est active ; si non, en démarre une pour accéder aux données utilisateur
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupère les préférences de thème et de couleur depuis la session, avec des valeurs par défaut
$theme = $_SESSION['theme'] ?? 'dark'; // Thème par défaut : sombre
$text_color = $_SESSION['text_color'] ?? '#00bcd4'; // Couleur d’accent par défaut : cyan
?>

<!-- Début du document HTML -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Définit l’encodage pour supporter les caractères spéciaux -->
    <meta charset="UTF-8">
    <!-- Assure une mise en page responsive adaptée aux mobiles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Charge le fichier CSS principal pour styliser la page -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <!-- Importe les polices Google Orbitron et Roboto pour une typographie personnalisée -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- Charge la bibliothèque noUiSlider pour les curseurs dans l’interface -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.5.0/nouislider.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.5.0/nouislider.min.js"></script>
    <!-- Titre de la page affiché dans l’onglet du navigateur -->
    <title>FolioVision</title>
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
    <!-- Définit des variables CSS personnalisées pour le thème -->
    <style>
        :root {
        <?php if ($theme === 'light'): ?>
            /* Variables pour le thème clair */
            --background-color: #ffffff; /* Fond principal */
            --container-background: #f5f5f5; /* Fond des conteneurs */
            --secondary-container-background: #e0e0e0; /* Fond secondaire */
            --text-color: #333333; /* Couleur du texte */
            --light-text-color: #000000; /* Texte clair */
        <?php else: ?>
            /* Variables pour le thème sombre (par défaut) */
            --background-color: #121212;
            --container-background: #1a1a1a;
            --secondary-container-background: #242424;
            --text-color: #e0e0e0;
            --light-text-color: #ffffff;
        <?php endif; ?>
            /* Couleur d’accent définie par l’utilisateur, protégée contre les injections */
            --accent-color: <?php echo htmlspecialchars($text_color); ?>;
        }
    </style>
</head>
<body>
<!-- En-tête minimal avec logo et menu utilisateur -->
<header class="minimal-header">
    <div class="header-left">
        <!-- Bouton pour afficher/masquer la sidebar -->
        <button class="sidebar-toggle">☰</button>
        <!-- Logo cliquable redirigeant vers la page d’accueil -->
        <a href="<?php echo BASE_URL; ?>index.php" class="logo">FolioVision</a>
    </div>
    <div class="user-info">
        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Menu déroulant pour l’utilisateur connecté -->
            <div class="user-dropdown">
                <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?><span class="arrow">▼</span></span>
                <div class="dropdown-content">
                    <a href="<?php echo BASE_URL; ?>pages/user/profile.php">Profil</a>
                    <a href="<?php echo BASE_URL; ?>pages/auth/logout.php">Déconnexion</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</header>

<!-- Barre latérale avec menu de navigation -->
<aside class="sidebar" id="sidebar">
    <nav>
        <ul>
            <li><a href="<?php echo BASE_URL; ?>pages/user/dashboard.php">Dashboard</a></li>
            <li><a href="<?php echo BASE_URL; ?>pages/user/accounts.php">Mes comptes</a></li>
            <li><a href="<?php echo BASE_URL; ?>pages/user/budget.php">Mes budjets</a></li>
            <li><a href="<?php echo BASE_URL; ?>pages/user/investments.php">Mes investissements</a></li>
            <li><a href="<?php echo BASE_URL; ?>pages/user/goals.php">Mes projets</a></li>
            <li><a href="<?php echo BASE_URL; ?>pages/user/settings.php">Paramètres</a></li>
            <li class="bonus-menu-item"><a href="<?php echo BASE_URL; ?>pages/public/bonus.php">Bonus</a></li>
        </ul>
    </nav>
</aside>

<!-- Charge le script JavaScript pour les interactions dynamiques (ex. toggle sidebar) -->
<script src="<?php echo BASE_URL; ?>assets/js/script.js"></script>
</body>
</html>