<?php
// Démarre la session PHP pour permettre l’accès aux variables de session (ex. état de connexion, préférences utilisateur)
session_start();
// Récupère le thème choisi par l’utilisateur depuis la session ; valeur par défaut : 'dark' si non défini
$theme = $_SESSION['theme'] ?? 'dark';
// Récupère la couleur d’accent personnalisée ; valeur par défaut : cyan (#00bcd4) si non définie
$text_color = $_SESSION['text_color'] ?? '#00bcd4';
?>

<!-- Début du document HTML avec la déclaration du type pour valider les standards web -->
<!DOCTYPE html>
<!-- Définit la langue du document comme le français -->
<html lang="fr">
<head>
    <!-- Spécifie l’encodage UTF-8 pour supporter les caractères spéciaux (accents, etc.) -->
    <meta charset="UTF-8">
    <!-- Rend la page responsive en ajustant la largeur au périphérique et en désactivant le zoom initial -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Charge la feuille de style principale en utilisant une constante BASE_URL pour un chemin absolu -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <!-- Importe les polices Google Orbitron (titres) et Roboto (texte) pour une typographie stylisée -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- Définit le titre de la page affiché dans l’onglet du navigateur -->
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
    <!-- Section de style interne pour définir des variables CSS personnalisées -->
    <style>
        /* :root définit des variables globales accessibles dans tout le CSS */
        :root {
        <?php if ($theme === 'light'): ?>
            /* Variables pour le thème clair : couleurs claires pour fond et texte sombre */
            --background-color: #ffffff; /* Fond principal blanc */
            --container-background: #f5f5f5; /* Fond des conteneurs légèrement gris */
            --secondary-container-background: #e0e0e0; /* Fond secondaire plus foncé */
            --text-color: #333333; /* Texte gris foncé pour contraste */
            --light-text-color: #000000; /* Texte noir pour éléments spécifiques */
        <?php else: ?>
            /* Variables pour le thème sombre : couleurs sombres pour fond et texte clair */
            --background-color: #121212; /* Fond principal très sombre */
            --container-background: #1a1a1a; /* Fond des conteneurs légèrement plus clair */
            --secondary-container-background: #242424; /* Fond secondaire gris sombre */
            --text-color: #e0e0e0; /* Texte gris clair pour lisibilité */
            --light-text-color: #ffffff; /* Texte blanc pour contraste maximal */
        <?php endif; ?>
            /* Couleur d’accent personnalisée, protégée contre les injections XSS avec htmlspecialchars */
            --accent-color: <?php echo htmlspecialchars($text_color); ?>;
        }
    </style>
</head>
<body>
<!-- En-tête visible de la page avec logo et navigation -->
<header>
    <div class="header-left">
        <!-- Logo cliquable redirigeant vers la page d’accueil -->
        <a href="<?php echo BASE_URL; ?>index.php" class="logo">FolioVision</a>
        <!-- Section de navigation avec un menu responsive -->
        <nav>
            <!-- Bouton toggle pour afficher/masquer le menu sur mobile -->
            <div class="menu-toggle">☰</div> <!-- Symbole hamburger -->
            <!-- Liste des liens de navigation -->
            <ul id="nav-menu">
                <!-- Menu déroulant pour les fonctionnalités -->
                <li class="dropdown">
                    <a href="" onclick="toggleDropdown()">Fonctionnalités</a> <!-- Lien vide déclenchant un script -->
                    <ul class="dropdown-menu">
                        <!-- Sous-options pour les fonctionnalités -->
                        <li><a href="<?php echo BASE_URL; ?>pages/public/features/budget.php">Gestion de Budget</a></li>
                        <li><a href="<?php echo BASE_URL; ?>pages/public/features/investments.php">Suivi des Investissements</a></li>
                        <li><a href="<?php echo BASE_URL; ?>pages/public/features/goals.php">Objectifs Financiers</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo BASE_URL; ?>pages/public/about.php">À propos</a></li>
                <li><a href="<?php echo BASE_URL; ?>pages/public/contact.php">Contact</a></li>
            </ul>
        </nav>
    </div>
    <!-- Section pour les actions utilisateur (connexion, profil, etc.) -->
    <div class="user-actions">
        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Si l’utilisateur est connecté, affiche son nom et des liens spécifiques -->
            <a href="<?php echo BASE_URL; ?>pages/user/profile.php" class="username-link">
                <!-- Affiche le nom d’utilisateur, protégé contre XSS -->
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </a>
            <a href="<?php echo BASE_URL; ?>pages/user/dashboard.php" class="btn">Tableau de bord</a>
            <a href="<?php echo BASE_URL; ?>pages/auth/logout.php" class="btn">Déconnexion</a>
        <?php else: ?>
            <!-- Si l’utilisateur n’est pas connecté, propose des options de connexion/inscription -->
            <a href="<?php echo BASE_URL; ?>pages/auth/login.php" class="btn">Se connecter</a>
            <a href="<?php echo BASE_URL; ?>pages/auth/register.php" class="btn">S’inscrire</a>
        <?php endif; ?>
    </div>
</header>

<!-- Charge un script JavaScript pour gérer les interactions dynamiques (ex. menu déroulant, toggle mobile) -->
<script src="<?php echo BASE_URL; ?>assets/js/script.js"></script>