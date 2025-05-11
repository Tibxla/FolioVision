<?php
// Inclut la configuration (ex. BASE_PATH) et l'en-tête standard du site
include '../../config/config.php';
include BASE_PATH . 'includes/header.php';
?>

    <!-- Conteneur principal pour la politique de confidentialité -->
    <div class="privacy-container">
        <h1>Politique de confidentialité</h1>
        <!-- Indique la date de la dernière mise à jour -->
        <p class="last-updated">Dernière mise à jour : 15 octobre 2023</p>

        <!-- Section d'introduction sur le respect de la vie privée -->
        <section>
            <h2>1. Introduction</h2>
            <p>Chez FolioVision, nous respectons votre vie privée. Cette politique décrit comment nous collectons, utilisons et protégeons vos données personnelles.</p>
        </section>

        <!-- Section listant les types de données collectées -->
        <section>
            <h2>2. Collecte des données</h2>
            <p>Nous collectons les données suivantes :</p>
            <ul>
                <li>Informations de compte (nom, email, mot de passe)</li>
                <li>Données de transaction (dépenses, revenus, investissements)</li>
                <li>Données techniques (adresse IP, type de navigateur)</li>
            </ul>
        </section>

        <!-- Section expliquant l'utilisation des données -->
        <section>
            <h2>3. Utilisation des données</h2>
            <p>Nous utilisons vos données pour :</p>
            <ul>
                <li>Fournir et améliorer nos services</li>
                <li>Personnaliser votre expérience</li>
                <li>Communiquer avec vous</li>
                <li>Assurer la sécurité du site</li>
            </ul>
        </section>

        <!-- Section sur le partage limité des données -->
        <section>
            <h2>4. Partage des données</h2>
            <p>Nous ne partageons vos données qu’avec des tiers de confiance pour fournir nos services (ex : hébergeurs, processeurs de paiement). Nous ne vendons pas vos données.</p>
        </section>

        <!-- Section sur les mesures de sécurité -->
        <section>
            <h2>5. Sécurité des données</h2>
            <p>Nous utilisons des mesures de sécurité techniques et organisationnelles pour protéger vos données contre l’accès non autorisé, la perte ou la destruction.</p>
        </section>

        <!-- Section sur les droits des utilisateurs -->
        <section>
            <h2>6. Droits des utilisateurs</h2>
            <p>Vous avez le droit d’accéder à vos données, de les rectifier, de les supprimer ou de limiter leur traitement. Contactez-nous à contact@foliovision.com pour exercer ces droits.</p>
        </section>

        <!-- Section sur l'utilisation des cookies -->
        <section>
            <h2>7. Cookies</h2>
            <p>Nous utilisons des cookies pour améliorer votre expérience. Vous pouvez gérer vos préférences via votre navigateur.</p>
        </section>

        <!-- Section sur les modifications de la politique -->
        <section>
            <h2>8. Modifications de la politique</h2>
            <p>Nous nous réservons le droit de modifier cette politique. Les changements importants seront communiqués par e-mail ou via le site.</p>
        </section>

        <!-- Section pour contacter FolioVision -->
        <section>
            <h2>9. Contact</h2>
            <p>Pour toute question, contactez-nous à contact@foliovision.com.</p>
        </section>
    </div>

<?php
// Inclut le pied de page pour finaliser la mise en page
include BASE_PATH . 'includes/footer.php';
?>