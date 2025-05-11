<?php
// Démarre la session pour accéder aux variables comme 'user_id'
session_start();
// Inclut les fichiers de configuration et de connexion à la base de données
include '../../config/config.php';
include '../../config/database.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Redirige vers la page de connexion si non authentifié et arrête le script
    header('Location: login.php');
    exit;
}

// Stocke l'ID de l'utilisateur connecté pour l'utiliser dans la requête SQL
$user_id = $_SESSION['user_id'];

// Prépare une requête SQL pour récupérer les comptes de l'utilisateur (ID, nom, solde, devise)
$stmt = $conn->prepare("SELECT account_id, account_name, balance, currency FROM accounts WHERE user_id = ?");
// Exécute la requête avec l'ID de l'utilisateur
$stmt->execute([$user_id]);
// Récupère tous les comptes sous forme de tableau associatif
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Inclut un en-tête minimal pour la mise en page (probablement un menu ou un logo) -->
<?php include BASE_PATH . 'includes/header_minimal.php'; ?>

    <!-- Zone principale pour afficher et gérer les comptes -->
    <div class="dashboard-content">
        <div class="accounts-container">
            <h1>Gestion des Comptes</h1> <!-- Titre de la section -->
            <!-- Bouton pour afficher le formulaire d'ajout de compte -->
            <button id="add-account-button" class="btn">Ajouter un compte</button>
            <!-- Formulaire d'ajout de compte, caché par défaut -->
            <div id="add-account-form" class="account-form-container" style="display: none;">
                <h2>Ajouter un nouveau compte</h2>
                <form id="account-form">
                    <label for="account_name">Nom du compte :</label>
                    <input type="text" id="account_name" name="account_name" required>

                    <label for="account_type">Type de compte :</label>
                    <!-- Liste déroulante pour choisir le type de compte -->
                    <select id="account_type" name="account_type" required>
                        <option value="bank">Banque</option>
                        <option value="cash">Espèces</option>
                        <option value="crypto">Crypto</option>
                        <option value="investment">Investissement</option>
                        <option value="credit_card">Carte de crédit</option>
                    </select>

                    <label for="bank_subtype">Sous-type :</label>
                    <!-- Sous-type, pertinent uniquement pour les comptes bancaires -->
                    <select id="bank_subtype" name="bank_subtype">
                        <option value="current">Compte courant</option>
                        <option value="savings">Compte d'épargne</option>
                    </select>

                    <label for="balance">Solde initial :</label>
                    <!-- Champ numérique avec décimales pour le solde -->
                    <input type="number" id="balance" name="balance" step="0.01" required>

                    <label for="currency">Devise :</label>
                    <!-- Champ texte avec 'EUR' par défaut -->
                    <input type="text" id="currency" name="currency" value="EUR" required>

                    <!-- Boutons pour soumettre ou annuler -->
                    <button type="submit" class="btn">Ajouter</button>
                    <button type="button" class="btn btn-secondary" id="cancel-add">Annuler</button>
                </form>
                <!-- Zone pour afficher les messages d'erreur -->
                <div id="error-message" class="error-message"></div>
            </div>

            <h2>Vos comptes</h2> <!-- Titre pour la liste des comptes -->
            <?php if (empty($accounts)): ?>
                <!-- Message si aucun compte n'est enregistré -->
                <p>Aucun compte pour le moment.</p>
            <?php else: ?>
                <!-- Liste des comptes existants -->
                <ul class="account-list">
                    <?php foreach ($accounts as $account): ?>
                        <li>
                            <!-- Lien vers les détails du compte, protégé contre XSS -->
                            <a href="account_details.php?account_id=<?php echo $account['account_id']; ?>">
                                <?php echo htmlspecialchars($account['account_name']); ?>
                            </a>
                            <?php echo htmlspecialchars($account['balance']); ?>
                            <?php echo htmlspecialchars($account['currency']); ?>
                            <div>
                                <!-- Boutons pour modifier ou supprimer, avec l'ID du compte en attribut -->
                                <button class="btn btn-secondary edit-account" data-id="<?php echo $account['account_id']; ?>">Modifier</button>
                                <button class="btn btn-secondary delete-account" data-id="<?php echo $account['account_id']; ?>">Supprimer</button>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

<!-- Inclut un pied de page pour fermer la mise en page -->
<?php include BASE_PATH . 'includes/footer.php'; ?>
