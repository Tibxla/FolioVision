<?php
// Inclut le fichier de configuration qui définit des constantes globales comme BASE_PATH
include '../../../config/config.php';

// Inclut le fichier d'en-tête commun à toutes les pages (contient probablement la barre de navigation, les balises HTML de base et les liens CSS)
include BASE_PATH . 'includes/header.php';
?>

    <!-- Conteneur principal pour styliser et structurer le contenu de la page -->
    <div class="feature-container">
        <!-- Titre principal de la page -->
        <h1>Suivi des Investissements</h1>
        <!-- Paragraphe introductif expliquant l'objectif de l'outil -->
        <p>Avec notre outil de suivi des investissements, vous pouvez gérer vos actifs et suivre leur performance en temps réel :</p>
        <!-- Liste non ordonnée présentant les fonctionnalités clés -->
        <ul>
            <!-- Chaque élément décrit une fonctionnalité spécifique -->
            <li>Support pour actions, cryptomonnaies, et immobilier</li>
            <li>Intégration des cours en temps réel via API</li>
            <li>Calcul des gains et pertes réalisés et non réalisés</li>
        </ul>
        <!-- Paragraphe final motivant l'utilisateur à utiliser l'outil -->
        <p>Optimisez votre portefeuille avec des données précises et à jour.</p>
    </div>

<?php
// Inclut le fichier de pied de page commun (contient probablement les scripts JS, la fermeture des balises HTML, etc.)
include BASE_PATH . 'includes/footer.php';
?>