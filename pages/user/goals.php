<?php
// Démarre la session pour gérer l'état de connexion de l'utilisateur
session_start();
// Inclut les fichiers de configuration et de connexion à la base de données
include '../../config/config.php';
include '../../config/database.php';

// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupère l'ID de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Calcule le total des épargnes des comptes bancaires de type 'savings'
$stmt = $conn->prepare("SELECT SUM(balance) as total_savings FROM accounts WHERE user_id = :user_id AND account_type = 'bank' AND bank_subtype = 'savings'");
$stmt->execute(['user_id' => $user_id]);
$total_savings = $stmt->fetch(PDO::FETCH_ASSOC)['total_savings'] ?? 0;

// Récupère tous les objectifs de l'utilisateur
$stmt = $conn->prepare("SELECT * FROM goals WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$goals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Met à jour le statut de chaque objectif selon son montant actuel et sa date d'échéance
foreach ($goals as &$goal) {
    $current = floatval($goal['current_amount']);
    $target = floatval($goal['target_amount']);
    if ($current >= $target) {
        $new_status = 'achieved';
    } elseif ($goal['due_date'] && $goal['due_date'] !== '0000-00-00') {
        $today = date('Y-m-d');
        if ($today > $goal['due_date'] && $current < $target) {
            $new_status = 'failed';
        } else {
            $new_status = 'in_progress';
        }
    } else {
        $new_status = 'in_progress';
    }
    // Met à jour la base de données si le statut a changé
    if ($goal['status'] !== $new_status) {
        $stmt = $conn->prepare("UPDATE goals SET status = :status WHERE goal_id = :goal_id");
        $stmt->execute(['status' => $new_status, 'goal_id' => $goal['goal_id']]);
        $goal['status'] = $new_status;
    }
}
unset($goal);

// Inclut l'en-tête minimal pour la structure de la page
include BASE_PATH . 'includes/header_minimal.php';
?>
    <!-- Débute le corps HTML avec une classe pour le style -->
    <body class="goals-page">

<!-- Conteneur principal du tableau de bord -->
<div class="dashboard-content">
    <!-- Conteneur pour la gestion des objectifs -->
    <div class="accounts-container">
        <!-- Titre de la page -->
        <h1>Gestion des Projets</h1>
        <!-- Affiche le total des épargnes -->
        <div class="total-savings">
            <h2>Total des comptes d'épargne : <?php echo number_format($total_savings, 2); ?> €</h2>
        </div>
        <!-- Bouton pour ouvrir la modale d'ajout d'objectif -->
        <button id="open-add-goal" class="btn">Ajouter un projet</button>

        <!-- Titre de la section des objectifs -->
        <h2>Mes projets</h2>
        <!-- Conteneur pour la liste des objectifs -->
        <div id="goal-list">
            <?php
            // Traduit les statuts en français
            $status_translations = [
                'in_progress' => 'En cours',
                'achieved' => 'Atteint',
                'failed' => 'Échoué'
            ];
            // Affiche chaque objectif avec ses détails
            foreach ($goals as $goal):
                $status_fr = $status_translations[$goal['status']] ?? $goal['status'];
                ?>
                <div class="goal-item">
                    <h3><?php echo htmlspecialchars($goal['goal_name']); ?></h3>
                    <p><strong>Montant actuel :</strong> <?php echo number_format($goal['current_amount'], 2); ?> <?php echo htmlspecialchars($goal['currency']); ?></p>
                    <p><strong>Montant cible :</strong> <?php echo number_format($goal['target_amount'], 2); ?> <?php echo htmlspecialchars($goal['currency']); ?></p>
                    <p><strong>Date d'échéance :</strong> <?php echo ($goal['due_date'] && $goal['due_date'] !== '0000-00-00') ? htmlspecialchars($goal['due_date']) : 'Aucune'; ?></p>
                    <p><strong>Statut :</strong> <?php echo htmlspecialchars($status_fr); ?></p>
                    <?php if (!empty($goal['comment'])): ?>
                        <p><strong>Commentaire :</strong> <?php echo htmlspecialchars($goal['comment']); ?></p>
                    <?php endif; ?>
                    <?php
                    // Calcule le pourcentage de progression
                    $progress = ($goal['target_amount'] > 0) ? ($goal['current_amount'] / $goal['target_amount']) * 100 : 0;
                    ?>
                    <!-- Barre de progression -->
                    <div class="progress-container">
                        <progress value="<?php echo $progress; ?>" max="100"></progress>
                        <span class="progress-percentage"><?php echo number_format($progress, 2); ?>%</span>
                    </div>
                    <!-- Boutons pour ajouter ou retirer de l'argent -->
                    <div class="button-group">
                        <button class="btn add-money" data-goal-id="<?php echo $goal['goal_id']; ?>">Ajouter de l'argent</button>
                        <button class="btn remove-money" data-goal-id="<?php echo $goal['goal_id']; ?>">Retirer de l'argent</button>
                    </div>
                    <!-- Menu d'actions pour modifier ou supprimer -->
                    <div class="goal-actions">
                        <span class="settings-icon">⚙️</span>
                        <div class="action-menu">
                            <a href="#" onclick="editGoal(<?php echo $goal['goal_id']; ?>); return false;">Modifier</a>
                            <a href="#" onclick="deleteGoal(<?php echo $goal['goal_id']; ?>); return false;">Supprimer</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Modale pour ajouter un nouvel objectif -->
        <div id="add-goal-modal" class="modal goal-modal">
            <div class="modal-content">
                <span class="close" id="close-add-goal-modal">×</span>
                <h2>Ajouter un nouveau projet</h2>
                <form id="add-goal-form">
                    <div class="form-group">
                        <label for="goal_name">Nom du projet :</label>
                        <input type="text" id="goal_name" name="goal_name" required>
                    </div>
                    <div class="form-group">
                        <label for="target_amount">Montant cible :</label>
                        <input type="number" id="target_amount" name="target_amount" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="currency">Devise :</label>
                        <select id="currency" name="currency" required>
                            <option value="EUR">EUR</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="initial_amount">Montant initial :</label>
                        <input type="number" id="initial_amount" name="initial_amount" step="0.01" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="due_date">Date d'échéance (optionnel) :</label>
                        <input type="date" id="due_date" name="due_date">
                    </div>
                    <div class="form-group">
                        <label for="comment">Commentaire :</label>
                        <textarea id="comment" name="comment"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Ajouter</button>
                        <button type="button" id="cancel-add-goal" class="btn btn-secondary">Annuler</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modale pour modifier un objectif -->
        <div id="edit-goal-modal" class="modal goal-modal">
            <div class="modal-content">
                <span class="close" id="close-edit-goal-modal">×</span>
                <h2>Modifier le projet</h2>
                <form id="edit-goal-form">
                    <input type="hidden" id="edit_goal_id" name="goal_id">
                    <div class="form-group">
                        <label for="edit_goal_name">Nom du projet :</label>
                        <input type="text" id="edit_goal_name" name="goal_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_target_amount">Montant cible :</label>
                        <input type="number" id="edit_target_amount" name="target_amount" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_currency">Devise :</label>
                        <select id="edit_currency" name="currency" required>
                            <option value="EUR">EUR</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_initial_amount">Montant actuel :</label>
                        <input type="number" id="edit_initial_amount" name="current_amount" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_due_date">Date d'échéance (optionnel) :</label>
                        <input type="date" id="edit_due_date" name="due_date">
                    </div>
                    <div class="form-group">
                        <label for="edit_comment">Commentaire :</label>
                        <textarea id="edit_comment" name="comment"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Sauvegarder</button>
                        <button type="button" id="cancel-edit-goal" class="btn btn-secondary">Annuler</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modale pour ajouter de l'argent à un objectif -->
        <div id="add-money-modal" class="modal goal-modal">
            <div class="modal-content">
                <span class="close" id="close-add-money-modal">×</span>
                <h2>Ajouter de l'argent</h2>
                <form id="add-money-form">
                    <input type="hidden" id="add_money_goal_id" name="goal_id">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label for="add_amount">Montant à ajouter :</label>
                        <input type="number" id="add_amount" name="amount" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modale pour retirer de l'argent d'un objectif -->
        <div id="remove-money-modal" class="modal goal-modal">
            <div class="modal-content">
                <span class="close" id="close-remove-money-modal">×</span>
                <h2>Retirer de l'argent</h2>
                <form id="remove-money-form">
                    <input type="hidden" id="remove_money_goal_id" name="goal_id">
                    <input type="hidden" name="action" value="remove">
                    <div class="form-group">
                        <label for="remove_amount">Montant à retirer :</label>
                        <input type="number" id="remove_amount" name="amount" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Retirer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Inclut le pied de page pour compléter la structure
include BASE_PATH . 'includes/footer.php';
?>