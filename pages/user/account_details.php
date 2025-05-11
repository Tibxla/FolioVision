<?php
// Démarre la session pour gérer les données utilisateur (ex. user_id)
session_start();

// Inclut les fichiers de configuration (constantes comme BASE_PATH) et de connexion à la base de données (objet PDO $conn)
include '../../config/config.php';
include '../../config/database.php';

// Vérifie si l'utilisateur est connecté via la présence de 'user_id' dans la session
if (!isset($_SESSION['user_id'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié et termine le script
    header('Location: login.php');
    exit;
}

// Récupère l'ID de l'utilisateur connecté depuis la session pour sécuriser les requêtes
$user_id = $_SESSION['user_id'];

// Récupère l'ID du compte depuis les paramètres GET (ex. account_details.php?account_id=1), ou null si absent
$account_id = $_GET['account_id'] ?? null;

// Vérifie si l'ID du compte est fourni, sinon affiche un message d'erreur et arrête le script
if (empty($account_id)) {
    echo "ID du compte manquant.";
    exit;
}

// Prépare une requête SQL sécurisée pour récupérer les détails du compte sélectionné
// Vérifie que le compte appartient à l'utilisateur connecté grâce à user_id
$stmt = $conn->prepare("SELECT * FROM accounts WHERE account_id = ? AND user_id = ?");
$stmt->execute([$account_id, $user_id]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

// Si aucun compte n'est trouvé ou s'il n'appartient pas à l'utilisateur, affiche un message et arrête le script
if (!$account) {
    echo "Compte non trouvé ou non autorisé.";
    exit;
}

// Récupère les catégories principales (celles sans parent_id) pour l'utilisateur ou globales (user_id IS NULL)
// Trie par ordre alphabétique pour une meilleure lisibilité
$stmt = $conn->prepare("SELECT category_id, name FROM categories WHERE parent_id IS NULL AND (user_id = ? OR user_id IS NULL) ORDER BY name ASC");
$stmt->execute([$user_id]);
$main_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupère tous les comptes de l'utilisateur pour afficher une liste dans la barre latérale
$stmt = $conn->prepare("SELECT account_id, account_name FROM accounts WHERE user_id = ?");
$stmt->execute([$user_id]);
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour traduire les types de compte en français pour une interface conviviale
function translateAccountType($type) {
    $translations = [
        'bank' => 'Banque',
        'cash' => 'Espèces',
        'crypto' => 'Crypto',
        'investment' => 'Investissement',
        'credit_card' => 'Carte de crédit',
    ];
    return $translations[$type] ?? $type; // Retourne la traduction ou le type brut si non trouvé
}

// Fonction pour traduire les sous-types de compte bancaire en français
function translateSubType($subType) {
    $translations = [
        'current' => 'Compte courant',
        'savings' => 'Compte d\'épargne',
    ];
    return $translations[$subType] ?? $subType; // Retourne la traduction ou le sous-type brut si non trouvé
}

// Inclut un en-tête minimal pour la mise en page de base (ex. navigation, styles)
include BASE_PATH . 'includes/header_minimal.php';
?>

    <!-- Conteneur principal pour structurer le tableau de bord -->
    <div class="dashboard-wrapper">
        <div class="dashboard-content">
            <div class="account-details-wrapper">
                <!-- Barre latérale pour naviguer entre les comptes de l'utilisateur -->
                <div class="account-list-sidebar">
                    <h3><a href="accounts.php" style="text-decoration: none; color: inherit;">Mes comptes</a></h3>
                    <ul>
                        <!-- Boucle pour afficher chaque compte avec un lien vers ses détails -->
                        <?php foreach ($accounts as $acc): ?>
                            <li>
                                <a href="account_details.php?account_id=<?php echo $acc['account_id']; ?>">
                                    <?php echo htmlspecialchars($acc['account_name']); // Protège contre les attaques XSS ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <!-- Section principale pour les détails du compte et la gestion des transactions -->
                <div class="account-details-container">
                    <!-- Affiche le nom du compte avec protection XSS -->
                    <h1><?php echo htmlspecialchars($account['account_name']); ?></h1>
                    <!-- Affiche le type de compte traduit -->
                    <p class="account-info"><strong>Type de compte :</strong> <?php echo translateAccountType(htmlspecialchars($account['account_type'])); ?></p>
                    <!-- Affiche le sous-type uniquement pour les comptes bancaires -->
                    <?php if ($account['account_type'] === 'bank'): ?>
                        <p class="account-info"><strong>Sous-type :</strong> <?php echo translateSubType(htmlspecialchars($account['bank_subtype'])); ?></p>
                    <?php endif; ?>
                    <!-- Affiche le solde avec la devise -->
                    <p class="account-info" id="balance-display"><strong>Solde :</strong> <?php echo htmlspecialchars($account['balance']); ?> <?php echo htmlspecialchars($account['currency']); ?></p>
                    <!-- Bouton pour ouvrir la modale d'ajout de transaction -->
                    <button id="open-transaction-modal" class="btn">Ajouter une transaction</button>

                    <!-- Modale pour ajouter une transaction -->
                    <div id="transaction-modal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span> <!-- Bouton de fermeture -->
                            <h2>Ajouter une transaction</h2>
                            <form id="add-transaction-form">
                                <!-- Champs cachés pour transmettre l'ID du compte et la devise -->
                                <input type="hidden" name="account_id" value="<?php echo $account_id; ?>">
                                <input type="hidden" name="currency" value="<?php echo htmlspecialchars($account['currency']); ?>">
                                <!-- Champ pour la date de la transaction -->
                                <label for="transaction_date">Date de transaction :</label>
                                <input type="date" id="transaction_date" name="transaction_date" required>
                                <!-- Sélection du type de paiement -->
                                <label for="payment_method">Type de paiement :</label>
                                <select id="payment_method" name="payment_method" required>
                                    <option value="bank_card">Carte bancaire (CB)</option>
                                    <option value="check">Chèque</option>
                                    <option value="transfer">Virement</option>
                                    <option value="check_deposit">Remise de chèque</option>
                                    <option value="direct_debit">Prélèvement</option>
                                    <option value="cash_deposit">Dépôt d’espèces</option>
                                    <option value="other">Autre</option>
                                </select>
                                <!-- Champ pour le montant avec précision décimale -->
                                <label for="amount">Montant :</label>
                                <input type="number" id="amount" name="amount" step="0.01" required>
                                <!-- Champ devise en lecture seule -->
                                <label for="currency">Devise :</label>
                                <input type="text" id="currency" name="currency" value="<?php echo htmlspecialchars($account['currency']); ?>" readonly>
                                <!-- Sélection du type de transaction (débit ou crédit) -->
                                <label for="type">Type :</label>
                                <select id="type" name="type" required>
                                    <option value="debit">Débit</option>
                                    <option value="credit">Crédit</option>
                                </select>
                                <!-- Sélection de la catégorie principale -->
                                <label for="main_category_id">Catégorie principale :</label>
                                <select id="main_category_id" name="main_category_id" required>
                                    <option value="">-- Sélectionner une catégorie principale --</option>
                                    <?php foreach ($main_categories as $category): ?>
                                        <option value="<?php echo $category['category_id']; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <!-- Sélection de la sous-catégorie (sera remplie via JavaScript) -->
                                <label for="sub_category_id">Sous-catégorie :</label>
                                <select id="sub_category_id" name="category_id">
                                    <option value="">-- Sélectionner une sous-catégorie --</option>
                                </select>
                                <!-- Boutons pour ouvrir les modales de création de catégories -->
                                <div class="button-group">
                                    <button type="button" id="open-main-category-modal" class="btn">Créer une catégorie principale</button>
                                    <button type="button" id="open-sub-category-modal" class="btn">Créer une sous-catégorie</button>
                                </div>
                                <!-- Champ pour une description optionnelle -->
                                <label for="description">Description :</label>
                                <input type="text" id="description" name="description">
                                <!-- Bouton de soumission du formulaire -->
                                <button type="submit" class="btn">Ajouter</button>
                            </form>

                            <!-- Modale pour créer une catégorie principale -->
                            <div id="main-category-modal" class="modal">
                                <div class="modal-content">
                                    <span class="close" id="close-main-category-modal">&times;</span>
                                    <h2>Créer une nouvelle catégorie principale</h2>
                                    <form id="add-main-category-form">
                                        <div class="form-group">
                                            <label for="main_category_name">Nom de la catégorie principale :</label>
                                            <input type="text" id="main_category_name" name="category_name" required>
                                        </div>
                                        <button type="submit" class="btn">Créer</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Modale pour créer une sous-catégorie -->
                            <div id="sub-category-modal" class="modal">
                                <div class="modal-content">
                                    <span class="close" id="close-sub-category-modal">&times;</span>
                                    <h2>Créer une nouvelle sous-catégorie</h2>
                                    <form id="add-sub-category-form">
                                        <div class="form-group">
                                            <label for="sub_category_name">Nom de la sous-catégorie :</label>
                                            <input type="text" id="sub_category_name" name="category_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="parent_category_id">Catégorie parente :</label>
                                            <select id="parent_category_id" name="parent_category_id" required>
                                                <option value="">-- Sélectionner une catégorie parente --</option>
                                                <!-- Options chargées dynamiquement via JavaScript -->
                                            </select>
                                        </div>
                                        <button type="submit" class="btn">Créer</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Modale générique pour créer une catégorie (potentiellement inutile) -->
                            <div id="category-modal" class="modal">
                                <div class="modal-content">
                                    <span class="close" id="close-category-modal">&times;</span>
                                    <h2>Créer une nouvelle catégorie</h2>
                                    <form id="add-category-form">
                                        <label for="category_name">Nom de la catégorie :</label>
                                        <input type="text" id="category_name" name="category_name" required>
                                        <button type="submit" class="btn">Créer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modale pour modifier une transaction existante -->
                    <div id="edit-transaction-modal" class="modal">
                        <div class="modal-content">
                            <span class="close" id="close-edit-transaction-modal">&times;</span>
                            <h2>Modifier la transaction</h2>
                            <form id="edit-transaction-form">
                                <!-- Champs cachés pour identifier la transaction et le compte -->
                                <input type="hidden" name="transaction_id" id="edit_transaction_id">
                                <input type="hidden" name="account_id" value="<?php echo $account_id; ?>">
                                <div class="form-group">
                                    <label for="edit_transaction_date">Date de transaction :</label>
                                    <input type="date" id="edit_transaction_date" name="transaction_date" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_payment_method">Type de paiement :</label>
                                    <select id="edit_payment_method" name="payment_method" required>
                                        <option value="bank_card">Carte bancaire (CB)</option>
                                        <option value="check">Chèque</option>
                                        <option value="transfer">Virement</option>
                                        <option value="check_deposit">Remise de chèque</option>
                                        <option value="direct_debit">Prélèvement</option>
                                        <option value="cash_deposit">Dépôt d’espèces</option>
                                        <option value="other">Autre</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_amount">Montant :</label>
                                    <input type="number" id="edit_amount" name="amount" step="0.01" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_currency">Devise :</label>
                                    <input type="text" id="edit_currency" name="currency" value="<?php echo htmlspecialchars($account['currency']); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="edit_type">Type :</label>
                                    <select id="edit_type" name="type" required>
                                        <option value="debit">Débit</option>
                                        <option value="credit">Crédit</option>
                                    </select>
                                </div>
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
                                    <label for="edit_sub_category_id">Sous-catégorie :</label>
                                    <select id="edit_sub_category_id" name="category_id">
                                        <option value="">-- Sélectionner une sous-catégorie --</option>
                                        <!-- Chargé dynamiquement via JavaScript -->
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_description">Description :</label>
                                    <input type="text" id="edit_description" name="description">
                                </div>
                                <button type="submit" class="btn">Modifier</button>
                            </form>
                        </div>
                    </div>

                    <!-- Modale pour copier une transaction vers un autre compte -->
                    <div id="copy-transaction-modal" class="modal copy-move-modal">
                        <div class="modal-content">
                            <span class="close" id="close-copy-modal">&times;</span>
                            <h2>Copier la transaction</h2>
                            <form id="copy-transaction-form">
                                <input type="hidden" name="transaction_id" id="copy_transaction_id">
                                <div class="form-group">
                                    <label for="copy-account-search">Rechercher un compte :</label>
                                    <input type="text" id="copy-account-search" placeholder="Tapez pour filtrer les comptes">
                                </div>
                                <div class="account-selection">
                                    <div class="account-list" id="copy-account-list"></div> <!-- Liste remplie via JavaScript -->
                                </div>
                                <button type="submit" class="btn">Copier</button>
                            </form>
                        </div>
                    </div>

                    <!-- Modale pour déplacer une transaction vers un autre compte -->
                    <div id="move-transaction-modal" class="modal copy-move-modal">
                        <div class="modal-content">
                            <span class="close" id="close-move-modal">&times;</span>
                            <h2>Déplacer la transaction</h2>
                            <form id="move-transaction-form">
                                <input type="hidden" name="transaction_id" id="move_transaction_id">
                                <div class="form-group">
                                    <label for="move-account-search">Rechercher un compte :</label>
                                    <input type="text" id="move-account-search" placeholder="Tapez pour filtrer les comptes">
                                </div>
                                <div class="account-selection">
                                    <div class="account-list" id="move-account-list"></div> <!-- Liste remplie via JavaScript -->
                                </div>
                                <button type="submit" class="btn">Déplacer</button>
                            </form>
                        </div>
                    </div>

                    <!-- Formulaire pour filtrer les transactions par plage de dates -->
                    <h2>Filtrer les transactions</h2>
                    <form id="transaction-filter-form">
                        <label for="start_date">Date de début :</label>
                        <input type="date" id="start_date" name="start_date">
                        <label for="end_date">Date de fin :</label>
                        <input type="date" id="end_date" name="end_date">
                        <button type="submit" class="btn">Filtrer</button>
                    </form>

                    <!-- Conteneur pour afficher les transactions (chargées via JavaScript) -->
                    <div id="transaction-list">
                        <!-- Les transactions apparaîtront ici -->
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
// Inclut le pied de page pour finaliser la mise en page (ex. scripts JS, fermeture HTML)
include BASE_PATH . 'includes/footer.php';
?>