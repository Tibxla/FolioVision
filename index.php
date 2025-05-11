<?php
// Inclut le fichier de configuration générale contenant les constantes et paramètres de l'application
include 'config/config.php';

// Inclut le header complet avec la navigation et les styles pour une page d'accueil attrayante
include BASE_PATH . 'includes/header.php';
?>

<!-- Début de la partie HTML pour la page d'accueil -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> <!-- Définit l'encodage pour supporter les caractères spéciaux -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Assure une compatibilité mobile -->
    <title>FolioVision - Gestion Financière Simplifiée</title> <!-- Titre optimisé pour le référencement et l'utilisateur -->
    <!-- Importe les polices Google Orbitron et Roboto pour une typographie moderne -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
<!-- Section héroïque : Sert d'introduction principale pour capter l'attention -->
<div class="hero">
    <h1>Bienvenue sur FolioVision</h1> <!-- Titre accrocheur -->
    <p>Votre solution tout-en-un pour la gestion de budget, de portefeuille et d'investissement.</p> <!-- Description concise -->
    <!-- Bouton d'appel à l'action menant à la page d'inscription -->
    <a href="pages/auth/register.php" class="btn">Commencez dès maintenant</a>
</div>

<!-- Section des fonctionnalités : Présente les avantages clés de l'application -->
<div class="features">
    <!-- Première fonctionnalité : Gestion de budget -->
    <div class="feature">
        <h2>Gestion de Budget</h2>
        <p>Suivez vos dépenses et revenus, définissez des budgets personnalisés et recevez des alertes en temps réel.</p>
    </div>
    <!-- Deuxième fonctionnalité : Suivi des investissements -->
    <div class="feature">
        <h2>Suivi des Investissements</h2>
        <p>Gérez vos actifs, suivez leurs performances et visualisez vos gains et pertes.</p>
    </div>
    <!-- Troisième fonctionnalité : Objectifs financiers -->
    <div class="feature">
        <h2>Objectifs Financiers</h2>
        <p>Définissez et suivez vos objectifs d'épargne ou de remboursement de dettes avec des outils intuitifs.</p>
    </div>
</div>

<!-- Section des statistiques : Renforce la crédibilité avec des chiffres clés -->
<div class="stats">
    <h2>Pourquoi choisir FolioVision ?</h2> <!-- Titre de la section -->
    <!-- Statistique sur la sécurité -->
    <div class="stat">
        <h3>100%</h3>
        <p>Sécurisé</p>
    </div>
    <!-- Statistique sur le support -->
    <div class="stat">
        <h3>24/7</h3>
        <p>Support</p>
    </div>
    <!-- Statistique sur les utilisateurs -->
    <div class="stat">
        <h3>10k+</h3>
        <p>Utilisateurs satisfaits</p>
    </div>
</div>

<!-- Section d'appel à l'action : Encourage une inscription immédiate -->
<div class="cta">
    <h2>Prêt à prendre le contrôle de vos finances ?</h2> <!-- Message motivant -->
    <!-- Lien vers la page d'inscription avec un bouton stylisé -->
    <a href="pages/auth/register.php" class="btn">Inscrivez-vous maintenant</a>
</div>

<?php
// Inclut le footer pour fermer la page avec des éléments comme des scripts ou un pied de page
include BASE_PATH . 'includes/footer.php';
?>
</body>
</html>