<?php
// Inclut la configuration (ex. BASE_PATH, BASE_URL) et l'en-tête du site
include '../../config/config.php';
include BASE_PATH . 'includes/header.php';
?>

    <!-- Conteneur principal pour la page de contact -->
    <div class="contact-container">
        <h1>Contactez-nous</h1>
        <!-- Texte d'introduction expliquant l'objectif de la page -->
        <p>Nous sommes à votre disposition pour répondre à vos questions ou recevoir vos suggestions. Utilisez le formulaire ci-dessous ou contactez-nous directement.</p>
        <h2>Formulaire de contact</h2>
        <!-- Formulaire pour soumettre un message -->
        <form id="contact-form">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" required> <!-- Champ obligatoire pour le nom -->
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required> <!-- Champ obligatoire pour l'email -->
            <label for="message">Message :</label>
            <textarea id="message" name="message" rows="5" required></textarea> <!-- Champ obligatoire pour le message -->
            <button type="submit">Envoyer</button> <!-- Bouton pour soumettre le formulaire -->
        </form>
        <!-- Section pour les coordonnées directes -->
        <h2>Coordonnées</h2>
        <p>Email : contact@foliovision.com</p>
        <p>Téléphone : +33 1 23 45 67 89</p>
        <p>Adresse : 123 Rue de la Finance, 75001 Paris, France</p>
    </div>

    <!-- Modale pour afficher des messages (ex. confirmation ou erreur) -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span> <!-- Bouton de fermeture -->
            <p id="modal-message"></p> <!-- Contenu du message affiché dynamiquement -->
        </div>
    </div>

    <!-- Inclut un script JavaScript pour gérer la soumission du formulaire et la modale -->
    <script src="<?php echo BASE_URL; ?>assets/js/script.js"></script>

<?php
// Inclut le pied de page pour finaliser la mise en page
include BASE_PATH . 'includes/footer.php';
?>