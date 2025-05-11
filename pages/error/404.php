<?php
include '../../config/config.php';  // Remonte de deux niveaux pour atteindre config/
include BASE_PATH . 'includes/header.php';  // Inclut l'en-tête du site
?>

    <div class="error-container">
        <h1>Page non trouvée</h1>
        <p>La page que vous cherchez n'existe pas ou a été déplacée.</p>
        <a href="<?php echo BASE_URL; ?>index.php" class="btn">Retour à l'accueil</a>
    </div>

<?php
include BASE_PATH . 'includes/footer.php';  // Inclut le pied de page du site
?>