<?php
// Initialise la session pour suivre l'utilisateur connecté
session_start();
// Charge les fichiers de configuration et de connexion à la base de données
include '../../config/config.php';
include '../../config/database.php';

// Vérifie la connexion de l'utilisateur et redirige vers la page de login si nécessaire
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupère l'identifiant de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Calcule le solde total des comptes de l'utilisateur
$stmt = $conn->prepare("SELECT SUM(balance) as total_balance FROM accounts WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$total_balance = $stmt->fetch(PDO::FETCH_ASSOC)['total_balance'] ?? 0;

// Récupère les soldes mensuels des 6 derniers mois pour l'évolution
$monthly_balances = [];
for ($i = 5; $i >= 0; $i--) {
    $date = date('Y-m', strtotime("-$i months"));
    $stmt = $conn->prepare("SELECT SUM(balance) as balance FROM accounts WHERE user_id = :user_id AND DATE_FORMAT(created_at, '%Y-%m') <= :date");
    $stmt->execute(['user_id' => $user_id, 'date' => $date]);
    $monthly_balances[$date] = $stmt->fetch(PDO::FETCH_ASSOC)['balance'] ?? 0;
}

// Récupère les budgets actifs avec les montants dépensés
$stmt = $conn->prepare("SELECT b.*, c.name as category_name, 
                        (SELECT SUM(t.amount) FROM transactions t WHERE t.category_id = b.category_id AND t.transaction_date BETWEEN b.start_date AND b.end_date) as used_amount 
                        FROM budgets b 
                        LEFT JOIN categories c ON b.category_id = c.category_id 
                        WHERE b.user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$budgets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupère les 5 dernières transactions de l'utilisateur
$stmt = $conn->prepare("SELECT t.*, a.account_name, c.name as category_name 
                        FROM transactions t 
                        LEFT JOIN accounts a ON t.account_id = a.account_id 
                        LEFT JOIN categories c ON t.category_id = c.category_id 
                        WHERE a.user_id = :user_id 
                        ORDER BY t.transaction_date DESC LIMIT 5");
$stmt->execute(['user_id' => $user_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcule la valeur totale des investissements
$stmt = $conn->prepare("SELECT SUM(current_price * quantity) as total_investments 
                        FROM investments 
                        WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$total_investments = $stmt->fetch(PDO::FETCH_ASSOC)['total_investments'] ?? 0;

// Récupère la répartition des investissements par type d'actif
$stmt = $conn->prepare("SELECT asset_type, SUM(current_price * quantity) as total 
                        FROM investments 
                        WHERE user_id = :user_id 
                        GROUP BY asset_type");
$stmt->execute(['user_id' => $user_id]);
$investment_types = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupère les objectifs financiers en cours
$stmt = $conn->prepare("SELECT * FROM goals WHERE user_id = :user_id AND status = 'in_progress'");
$stmt->execute(['user_id' => $user_id]);
$goals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inclut l'en-tête de la page
include BASE_PATH . 'includes/header_minimal.php';
?>

    <!-- Conteneur global du tableau de bord -->
    <div class="dashboard-wrapper">
        <!-- Contenu principal -->
        <main class="dashboard-content">
            <!-- Titre principal -->
            <h1 class="dashboard-title">Tableau de bord financier</h1>

            <!-- Affichage du solde total -->
            <section class="dashboard-section">
                <div class="balance-card">
                    <h2>Solde total</h2>
                    <p class="balance-amount"><?php echo number_format($total_balance, 2); ?> €</p>
                    <button class="btn" onclick="window.location.href='<?php echo BASE_URL; ?>pages/user/accounts.php'">Gérer les comptes</button>
                </div>
            </section>

            <!-- Graphique de l'évolution du solde -->
            <section class="dashboard-section">
                <h2>Évolution du solde</h2>
                <canvas id="balance-chart" width="400" height="200"></canvas>
            </section>

            <!-- Liste des budgets actifs -->
            <section class="dashboard-section">
                <h2>Budgets actifs</h2>
                <?php if (empty($budgets)): ?>
                    <p>Aucun budget actif pour le moment.</p>
                <?php else: ?>
                    <div id="budget-list">
                        <?php foreach ($budgets as $budget): ?>
                            <div class="budget-item">
                                <p><strong>Catégorie :</strong> <?php echo htmlspecialchars($budget['category_name']); ?></p>
                                <p><strong>Montant alloué :</strong> <?php echo number_format($budget['budget_amount'], 2); ?> €</p>
                                <p><strong>Dépensé :</strong> <?php echo number_format($budget['used_amount'], 2); ?> €</p>
                                <?php
                                // Calcul du pourcentage dépensé
                                $percentage = ($budget['budget_amount'] > 0) ? ($budget['used_amount'] / $budget['budget_amount']) * 100 : 0;
                                ?>
                                <!-- Barre de progression -->
                                <div class="progress-container">
                                    <progress value="<?php echo $percentage; ?>" max="100"></progress>
                                    <span class="progress-percentage"><?php echo number_format($percentage, 2); ?>%</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <button class="btn" onclick="window.location.href='<?php echo BASE_URL; ?>pages/user/budget.php'">Gérer les budgets</button>
            </section>

            <!-- Tableau des transactions récentes -->
            <section class="dashboard-section">
                <h2>Transactions récentes</h2>
                <table>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Compte</th>
                        <th>Catégorie</th>
                        <th>Montant</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo $transaction['transaction_date']; ?></td>
                            <td><?php echo htmlspecialchars($transaction['account_name']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['category_name']); ?></td>
                            <td><?php echo number_format($transaction['amount'], 2); ?> €</td>
                            <td><?php echo htmlspecialchars($transaction['description']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <button class="btn" onclick="window.location.href='<?php echo BASE_URL; ?>pages/user/accounts.php'">Voir toutes les transactions</button>
            </section>

            <!-- Aperçu des investissements -->
            <section class="dashboard-section">
                <h2>Investissements</h2>
                <div class="balance-card">
                    <h3>Valeur totale des investissements</h3>
                    <p class="balance-amount"><?php echo number_format($total_investments, 2); ?> €</p>
                </div>
                <?php if (!empty($investment_types)): ?>
                    <!-- Graphique de répartition des investissements -->
                    <canvas id="investment-chart" width="400" height="200"></canvas>
                <?php endif; ?>
                <button class="btn" onclick="window.location.href='<?php echo BASE_URL; ?>pages/user/investments.php'">Gérer les investissements</button>
            </section>

            <!-- Liste des objectifs financiers -->
            <section class="dashboard-section">
                <h2>Objectifs financiers en cours</h2>
                <div id="goal-list">
                    <?php if (empty($goals)): ?>
                        <p>Aucun objectif financier en cours pour le moment.</p>
                    <?php else: ?>
                        <?php foreach ($goals as $goal): ?>
                            <div class="goal-item">
                                <h3><?php echo htmlspecialchars($goal['goal_name']); ?></h3>
                                <p><strong>Montant actuel :</strong> <?php echo number_format($goal['current_amount'], 2); ?> <?php echo htmlspecialchars($goal['currency']); ?></p>
                                <p><strong>Montant cible :</strong> <?php echo number_format($goal['target_amount'], 2); ?> <?php echo htmlspecialchars($goal['currency']); ?></p>
                                <?php
                                // Calcul de la progression vers l'objectif
                                $progress = ($goal['target_amount'] > 0) ? ($goal['current_amount'] / $goal['target_amount']) * 100 : 0;
                                ?>
                                <!-- Barre de progression -->
                                <div class="progress-container">
                                    <progress value="<?php echo $progress; ?>" max="100"></progress>
                                    <span class="progress-percentage"><?php echo number_format($progress, 2); ?>%</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button class="btn" onclick="window.location.href='<?php echo BASE_URL; ?>pages/user/goals.php'">Gérer les objectifs</button>
            </section>
        </main>
    </div>

    <!-- Chargement de Chart.js pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js" async onload="initCharts()"></script>
    <script>
        // Initialise les graphiques après le chargement de Chart.js
        function initCharts() {
            // Données pour le graphique du solde
            const balanceData = {
                labels: <?php echo json_encode(array_keys($monthly_balances)); ?>,
                datasets: [{
                    label: 'Solde mensuel',
                    data: <?php echo json_encode(array_values($monthly_balances)); ?>,
                    borderColor: 'rgba(0, 188, 212, 1)',
                    backgroundColor: 'rgba(0, 188, 212, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            };

            // Configuration du graphique du solde
            const balanceConfig = {
                type: 'line',
                data: balanceData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Évolution du solde sur 6 mois', color: '#e0e0e0' }
                    },
                    scales: {
                        x: { ticks: { color: '#e0e0e0' } },
                        y: { ticks: { color: '#e0e0e0' }, beginAtZero: true }
                    }
                }
            };

            // Création du graphique du solde
            const balanceChart = new Chart(document.getElementById('balance-chart'), balanceConfig);

            // Graphique des investissements si données disponibles
            <?php if (!empty($investment_types)): ?>
            const investmentData = {
                labels: <?php echo json_encode(array_column($investment_types, 'asset_type')); ?>,
                datasets: [{
                    label: 'Répartition des investissements',
                    data: <?php echo json_encode(array_column($investment_types, 'total')); ?>,
                    backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0'],
                    borderColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0'],
                    borderWidth: 1
                }]
            };

            const investmentConfig = {
                type: 'pie',
                data: investmentData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top', labels: { color: '#e0e0e0' } },
                        title: { display: true, text: 'Répartition des investissements', color: '#e0e0e0' }
                    }
                }
            };

            const investmentChart = new Chart(document.getElementById('investment-chart'), investmentConfig);
            <?php endif; ?>
        }
    </script>

<?php
// Inclut le pied de page
include BASE_PATH . 'includes/footer.php';
?>