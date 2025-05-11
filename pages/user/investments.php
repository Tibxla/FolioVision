<?php
// Initialise la session pour suivre l'utilisateur
session_start();
// Charge les fichiers de configuration et de connexion à la base de données
include '../../config/config.php';
include '../../config/database.php';

// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupère l'identifiant de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Ajoute l'en-tête minimal pour la structure de la page
include BASE_PATH . 'includes/header_minimal.php';
?>
    <!-- Définit le corps de la page avec une classe spécifique -->
    <body class="investments-page">
    <!-- Conteneur principal pour le contenu du tableau de bord -->
    <div class="dashboard-content">
        <!-- Section dédiée à la gestion des investissements -->
        <div class="accounts-container">
            <!-- Titre de la page -->
            <h1>Gestion des Investissements</h1>
            <!-- Bouton pour afficher le formulaire d'ajout -->
            <button id="open-add-investment" class="btn">Ajouter un investissement</button>

            <!-- Formulaire d'ajout d'investissement, masqué par défaut -->
            <div id="add-investment-form-container" class="account-form-container" style="display: none;">
                <h2>Ajouter un nouvel investissement</h2>
                <form id="add-investment-form">
                    <div class="form-group">
                        <label for="investment_type">Type d'investissement :</label>
                        <select id="investment_type" name="investment_type" required>
                            <option value="">-- Sélectionner un type --</option>
                            <option value="stock">Actions</option>
                            <option value="crypto">Crypto-monnaie</option>
                            <option value="real_estate">Immobilier</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="investment_name">Nom de l'investissement :</label>
                        <input type="text" id="investment_name" name="investment_name" required>
                    </div>
                    <div class="form-group">
                        <label for="investment_amount">Montant investi (€) :</label>
                        <input type="number" id="investment_amount" name="investment_amount" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="current_price">Valeur actuelle (€) :</label>
                        <input type="number" id="current_price" name="current_price" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantité :</label>
                        <input type="number" id="quantity" name="quantity" step="1" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="investment_date">Date d'investissement :</label>
                        <input type="date" id="investment_date" name="investment_date" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Ajouter</button>
                        <button type="button" id="cancel-add-investment" class="btn btn-secondary">Annuler</button>
                    </div>
                </form>
            </div>

            <!-- Affiche la liste des investissements existants -->
            <h2>Vos investissements</h2>
            <ul id="investment-list" class="account-list">
                <!-- Contenu généré dynamiquement par JavaScript -->
            </ul>

            <!-- Modale pour éditer un investissement -->
            <div id="edit-investment-modal" class="modal">
                <div class="modal-content">
                    <span class="close" id="close-edit-investment-modal">×</span>
                    <h2>Modifier l'investissement</h2>
                    <form id="edit-investment-form">
                        <input type="hidden" id="edit_investment_id" name="investment_id">
                        <div class="form-group">
                            <label for="edit_investment_type">Type d'investissement :</label>
                            <select id="edit_investment_type" name="investment_type" required>
                                <option value="">-- Sélectionner un type --</option>
                                <option value="stock">Actions</option>
                                <option value="crypto">Crypto-monnaie</option>
                                <option value="real_estate">Immobilier</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_investment_name">Nom de l'investissement :</label>
                            <input type="text" id="edit_investment_name" name="investment_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_investment_amount">Montant investi (€) :</label>
                            <input type="number" id="edit_investment_amount" name="investment_amount" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_current_price">Valeur actuelle (€) :</label>
                            <input type="number" id="edit_current_price" name="current_price" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_quantity">Quantité :</label>
                            <input type="number" id="edit_quantity" name="quantity" step="1" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_investment_date">Date d'investissement :</label>
                            <input type="date" id="edit_investment_date" name="investment_date" required>
                        </div>
                        <button type="submit" class="btn">Sauvegarder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
// Intègre le pied de page pour compléter la structure
include BASE_PATH . 'includes/footer.php';
?>