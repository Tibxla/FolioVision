<?php
// Initialisation de la session pour gérer l'état de connexion
session_start();
// Chargement des fichiers de configuration et de connexion à la base de données
include '../../config/config.php';
include '../../config/database.php';

// Vérification de la connexion utilisateur, redirection vers la page de login si non connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupération de l'identifiant de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Requête pour obtenir les catégories principales de l'utilisateur ou globales
$stmt = $conn->prepare("SELECT category_id, name FROM categories WHERE parent_id IS NULL AND (user_id = ? OR user_id IS NULL) ORDER BY name ASC");
$stmt->execute([$user_id]);
$main_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Requête pour obtenir les comptes associés à l'utilisateur
$stmt = $conn->prepare("SELECT account_id, account_name FROM accounts WHERE user_id = ?");
$stmt->execute([$user_id]);
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inclusion de l'en-tête minimal pour la mise en page
include BASE_PATH . 'includes/header_minimal.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Budgets</title>
</head>
<body class="budget-page">
<div class="dashboard-content">
    <div class="accounts-container">
        <h1>Gérer les Budgets</h1>
        <button id="open-add-budget" class="btn">Ajouter un budget</button>

        <!-- Section pour ajouter un nouveau budget, cachée par défaut -->
        <div id="add-budget-form-container" class="account-form-container" style="display: none;">
            <h2>Créer un nouveau budget</h2>
            <form id="add-budget-form">
                <div class="form-group">
                    <label for="main_category_id">Catégorie principale :</label>
                    <select id="main_category_id" name="main_category_id" required>
                        <option value="">-- Sélectionner une catégorie principale --</option>
                        <?php foreach ($main_categories as $category): ?>
                            <option value="<?php echo $category['category_id']; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="sub_category_id">Sous-catégorie (optionnelle) :</label>
                    <select id="sub_category_id" name="sub_category_id">
                        <option value="">-- Sélectionner une sous-catégorie --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="budget_amount">Montant du budget :</label>
                    <input type="number" id="budget_amount" name="budget_amount" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label for="period">Période :</label>
                    <select id="period" name="period" required>
                        <option value="monthly">Mensuel</option>
                        <option value="quarterly">Trimestriel</option>
                        <option value="annually">Annuel</option>
                        <option value="custom">Personnalisé</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="start_month">Mois de début :</label>
                    <input type="month" id="start_month" name="start_month" value="<?php echo date('Y-m'); ?>" required>
                </div>

                <div class="form-group">
                    <label for="end_month">Mois de fin (optionnel) :</label>
                    <input type="month" id="end_month" name="end_month">
                </div>

                <div class="form-group">
                    <label>Comptes associés :</label>
                    <div id="account-list">
                        <?php foreach ($accounts as $account): ?>
                            <div class="account-item" style="display: flex; justify-content: space-between; align-items: center;">
                                <label for="account_<?php echo $account['account_id']; ?>"><?php echo htmlspecialchars($account['account_name']); ?></label>
                                <input type="checkbox" name="accounts[]" value="<?php echo $account['account_id']; ?>" id="account_<?php echo $account['account_id']; ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="carry_over_under">Reporter le montant restant :</label>
                    <input type="checkbox" id="carry_over_under" name="carry_over_under">
                    <small>Permet de reporter le solde non utilisé à la période suivante.</small>
                </div>

                <div class="form-group">
                    <label for="carry_over_over">Reporter le montant dépassé :</label>
                    <input type="checkbox" id="carry_over_over" name="carry_over_over">
                    <small>Permet de reporter le dépassement du budget à la période suivante.</small>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Créer le budget</button>
                    <button type="button" id="cancel-add-budget" class="btn btn-secondary">Annuler</button>
                </div>
            </form>
        </div>

        <!-- Affichage de la liste des budgets existants -->
        <h2>Mes budgets</h2>
        <ul id="budget-list" class="account-list">
            <!-- Les budgets seront insérés ici par JavaScript -->
        </ul>

        <!-- Fenêtre modale pour modifier un budget -->
        <div id="edit-budget-modal" class="modal">
            <div class="modal-content">
                <span class="close" id="close-edit-budget-modal">×</span>
                <h2>Modifier le budget</h2>
                <form id="edit-budget-form">
                    <input type="hidden" id="edit_budget_id" name="budget_id">
                    <div class="form-group">
                        <label for="edit_main_category_id">Catégorie principale :</label>
                        <select id="edit_main_category_id" name="main_category_id" required>
                            <option value="">-- Sélectionner une catégorie principale --</option>
                            <?php foreach ($main_categories as $category): ?>
                                <option value="<?php echo $category['category_id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_sub_category_id">Sous-catégorie (optionnelle) :</label>
                        <select id="edit_sub_category_id" name="sub_category_id">
                            <option value="">-- Sélectionner une sous-catégorie --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_budget_amount">Montant du budget :</label>
                        <input type="number" id="edit_budget_amount" name="budget_amount" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_period">Période :</label>
                        <select id="edit_period" name="period" required>
                            <option value="monthly">Mensuel</option>
                            <option value="quarterly">Trimestriel</option>
                            <option value="annually">Annuel</option>
                            <option value="custom">Personnalisé</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_start_month">Mois de début :</label>
                        <input type="month" id="edit_start_month" name="start_month" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_end_month">Mois de fin (optionnel) :</label>
                        <input type="month" id="edit_end_month" name="end_month">
                    </div>
                    <div class="form-group">
                        <label>Comptes associés :</label>
                        <div id="edit_account_list">
                            <!-- Les comptes seront chargés dynamiquement -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_carry_over_under">Reporter le montant restant :</label>
                        <input type="checkbox" id="edit_carry_over_under" name="carry_over_under">
                    </div>
                    <div class="form-group">
                        <label for="edit_carry_over_over">Reporter le montant dépassé :</label>
                        <input type="checkbox" id="edit_carry_over_over" name="carry_over_over">
                    </div>
                    <button type="submit" class="btn">Sauvegarder</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Inclusion du pied de page pour finaliser la structure
include BASE_PATH . 'includes/footer.php';
?>
</body>
</html>