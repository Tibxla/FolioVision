<?php
// Inclut le fichier de configuration pour accéder à des constantes comme BASE_PATH
include '../../../config/config.php';

// Inclut l'en-tête standard du site pour une mise en page cohérente
include BASE_PATH . 'includes/header.php';
?>

    <!-- Conteneur principal pour organiser le contenu de la page -->
    <div class="feature-container">
        <!-- Titre principal de la page -->
        <h1>Objectifs Financiers</h1>
        <!-- Introduction à la fonctionnalité de suivi des objectifs -->
        <p>Atteignez vos objectifs financiers avec notre outil de suivi personnalisé :</p>
        <!-- Liste des principales fonctionnalités offertes -->
        <ul>
            <!-- Chaque élément met en avant une capacité spécifique de l'outil -->
            <li>Définissez des objectifs d'épargne ou de remboursement de dettes</li>
            <li>Suivez votre progression avec des alertes et des suggestions</li>
            <li>Visualisez vos progrès avec des graphiques intuitifs</li>
        </ul>
        <!-- Message motivant pour encourager l'utilisation de l'outil -->
        <p>Restez motivé et atteignez vos objectifs plus rapidement.</p>
    </div>

<?php
// Inclut le pied de page pour terminer la page de manière uniforme
include BASE_PATH . 'includes/footer.php';
?>