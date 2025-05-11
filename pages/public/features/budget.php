<?php
// Inclut le fichier de configuration pour utiliser des constantes comme BASE_PATH
include '../../../config/config.php';

// Inclut l'en-tête commun à toutes les pages du site
include BASE_PATH . 'includes/header.php';
?>

    <!-- Conteneur principal pour structurer le contenu -->
    <div class="feature-container">
        <!-- Titre principal de la page -->
        <h1>Gestion de Budget</h1>
        <!-- Description introductive de l'outil de gestion de budget -->
        <p>Notre outil de gestion de budget vous permet de suivre vos dépenses et revenus facilement. Avec des fonctionnalités comme :</p>
        <!-- Liste des fonctionnalités clés de l'outil -->
        <ul>
            <!-- Chaque élément détaille une caractéristique spécifique -->
            <li>Catégorisation automatique des transactions</li>
            <li>Alertes en temps réel pour les budgets dépassés</li>
            <li>Graphiques détaillés pour analyser vos habitudes financières</li>
        </ul>
        <!-- Invitation à utiliser l'outil pour améliorer sa gestion financière -->
        <p>Découvrez comment prendre le contrôle de vos finances dès aujourd'hui.</p>
    </div>

<?php
// Inclut le pied de page pour une fin de page cohérente
include BASE_PATH . 'includes/footer.php';
?>