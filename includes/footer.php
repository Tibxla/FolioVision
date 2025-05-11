<!-- Pied de page de l’application, affiché en bas de chaque page -->
<footer>
    <div class="footer-content">
        <!-- Section contenant les liens vers les pages légales -->
        <div class="footer-links">
            <!-- Lien vers les conditions d’utilisation, utilisant BASE_URL pour un chemin cohérent -->
            <a href="<?php echo BASE_URL; ?>pages/public/terms.php">Conditions d’utilisation</a> |
            <!-- Lien vers la politique de confidentialité -->
            <a href="<?php echo BASE_URL; ?>pages/public/privacy.php">Politique de confidentialité</a>
        </div>
        <!-- Slogan de l’application pour renforcer l’identité de marque -->
        <div class="footer-slogan">
            Prenez le contrôle de vos finances avec FolioVision.
        </div>
        <!-- Informations de copyright avec année fixe -->
        <div class="footer-copyright">
            © <?php echo date('Y'); ?> FolioVision. Tous droits réservés.
        </div>
    </div>
</footer>