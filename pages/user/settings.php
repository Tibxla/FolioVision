<?php
// Inclut les fichiers de configuration générale et de connexion à la base de données
// Ces fichiers définissent des constantes (comme BASE_PATH) et établissent la connexion PDO ($conn)
include '../../config/config.php';
include '../../config/database.php';

// Démarre la session pour gérer les données utilisateur persistantes (comme l'ID ou les préférences)
session_start();

// Vérifie si l'utilisateur est connecté en s'assurant que 'user_id' existe dans la session
if (!isset($_SESSION['user_id'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
    header('Location: login.php');
    exit; // Termine le script pour éviter toute exécution supplémentaire
}

// Récupère l'identifiant unique de l'utilisateur connecté à partir de la session
$user_id = $_SESSION['user_id'];

// Gestion de la sauvegarde d'un preset via une requête AJAX (soumission asynchrone depuis le formulaire)
if (isset($_POST['save_preset'])) {
    // Vérifie que les champs obligatoires ne sont pas vides pour éviter des données invalides
    if (empty($_POST['savename']) || empty($_POST['theme']) || empty($_POST['text_color'])) {
        // Retourne une réponse JSON avec un statut d'erreur si des champs sont manquants
        echo json_encode(['status' => 'error', 'message' => 'Tous les champs sont requis']);
        exit; // Arrête le script après la réponse
    }

    // Nettoie et récupère les données envoyées par le formulaire
    $savename = trim($_POST['savename']); // Supprime les espaces inutiles du nom du preset
    $theme = $_POST['theme']; // Récupère le thème choisi (ex. "dark" ou "light")
    $text_color = $_POST['text_color']; // Récupère la couleur du texte en format hexadécimal

    // Prépare une requête SQL sécurisée pour insérer le preset dans la table 'user_preferences'
    $stmt = $conn->prepare("INSERT INTO user_preferences (user_id, savename, theme, text_color) VALUES (?, ?, ?, ?)");

    // Exécute la requête avec les valeurs fournies et vérifie son succès
    if ($stmt->execute([$user_id, $savename, $theme, $text_color])) {
        // Récupère l'ID du preset nouvellement inséré pour référence future
        $pref_id = $conn->lastInsertId();
        // Retourne une réponse JSON indiquant le succès avec les détails du preset
        echo json_encode([
            'status' => 'success',
            'message' => 'Preset sauvegardé avec succès',
            'pref_id' => $pref_id,
            'savename' => $savename,
            'theme' => $theme,
            'text_color' => $text_color
        ]);
    } else {
        // En cas d'échec de la requête SQL, retourne une erreur avec les détails techniques
        echo json_encode(['status' => 'error', 'message' => 'Erreur SQL : ' . implode(', ', $stmt->errorInfo())]);
    }
    exit; // Termine le script après avoir envoyé la réponse AJAX
}

// Inclut un header minimal pour la mise en page (probablement avec navigation simplifiée)
include BASE_PATH . 'includes/header_minimal.php';

// Récupère tous les presets existants de l'utilisateur pour les afficher dans l'interface
$stmt = $conn->prepare("SELECT * FROM user_preferences WHERE user_id = ?");
$stmt->execute([$user_id]);
$presets = $stmt->fetchAll(PDO::FETCH_ASSOC); // Stocke les résultats sous forme de tableau associatif

// Gestion de l'application d'un preset lorsqu'un utilisateur clique sur "Appliquer" (via une requête GET)
if (isset($_GET['apply'])) {
    $pref_id = $_GET['apply']; // Récupère l'ID du preset à appliquer
    // Prépare une requête pour récupérer les détails du preset spécifié
    $stmt = $conn->prepare("SELECT theme, text_color FROM user_preferences WHERE pref_id = ? AND user_id = ?");
    $stmt->execute([$pref_id, $user_id]);
    $preset = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère le preset sous forme de tableau associatif

    if ($preset) {
        // Met à jour les variables de session avec les préférences du preset choisi
        $_SESSION['theme'] = $preset['theme'];
        $_SESSION['text_color'] = $preset['text_color'];

        // Persiste les préférences dans la table 'users' pour une application permanente
        $stmt = $conn->prepare("UPDATE users SET preferred_theme = ?, preferred_text_color = ? WHERE user_id = ?");
        $stmt->execute([$preset['theme'], $preset['text_color'], $user_id]);

        // Affiche un message de confirmation (optionnel, souvent remplacé par une gestion côté client)
        echo "<p>Thème appliqué : " . htmlspecialchars($preset['theme']) . " avec couleur de texte : " . htmlspecialchars($preset['text_color']) . "</p>";
    }
}

// Gestion de la suppression d'un preset lorsqu'un utilisateur clique sur "Supprimer" (via une requête GET)
if (isset($_GET['delete'])) {
    $pref_id = $_GET['delete']; // Récupère l'ID du preset à supprimer
    // Prépare et exécute une requête pour supprimer le preset de la base de données
    $stmt = $conn->prepare("DELETE FROM user_preferences WHERE pref_id = ? AND user_id = ?");
    $stmt->execute([$pref_id, $user_id]);
    // Redirige vers la même page pour rafraîchir la liste des presets
    header('Location: settings.php');
    exit;
}
?>

<!-- Début de la partie HTML pour afficher l'interface utilisateur -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> <!-- Définit l'encodage des caractères pour supporter les accents -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Assure une mise en page responsive -->
    <title>Préférences - FolioVision</title> <!-- Titre de la page affiché dans l'onglet du navigateur -->
    <!-- Importe les polices Google Orbitron et Roboto pour un design moderne et cohérent -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="preferences-container"> <!-- Conteneur principal pour styliser la section des préférences -->
    <h1>Préférences de Thème</h1> <!-- Titre principal de la page -->

    <!-- Section pour afficher la liste des presets existants -->
    <h2>Vos Presets</h2>
    <?php if (empty($presets)): ?>
        <!-- Message affiché si aucun preset n'a encore été créé -->
        <p>Aucun preset sauvegardé pour le moment.</p>
    <?php else: ?>
        <!-- Liste des presets sous forme d'éléments <ul> -->
        <ul>
            <?php foreach ($presets as $preset): ?>
                <li>
                    <!-- Affiche les détails du preset avec protection contre les attaques XSS -->
                    <?php echo htmlspecialchars($preset['savename']); ?> -
                    Thème : <?php echo htmlspecialchars($preset['theme']); ?>,
                    Couleur : <?php echo htmlspecialchars($preset['text_color']); ?>
                    <!-- Bouton pour appliquer le preset avec des attributs data pour une gestion JavaScript -->
                    <a href="?apply=<?php echo $preset['pref_id']; ?>"
                       class="btn btn-apply"
                       data-theme="<?php echo htmlspecialchars($preset['theme']); ?>"
                       data-text-color="<?php echo htmlspecialchars($preset['text_color']); ?>">
                        Appliquer
                    </a>
                    <!-- Bouton pour supprimer le preset avec une confirmation JavaScript -->
                    <a href="?delete=<?php echo $preset['pref_id']; ?>"
                       class="btn btn-secondary"
                       onclick="return confirm('Voulez-vous vraiment supprimer ce preset ?');">
                        Supprimer
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Formulaire pour créer un nouveau preset -->
    <h2>Créer un Nouveau Preset</h2>
    <form id="preset-form"> <!-- Identifiant pour une gestion JavaScript/AJAX -->
        <label for="savename">Nom du Preset :</label> <!-- Étiquette pour le champ de nom -->
        <input type="text" id="savename" name="savename" required> <!-- Champ obligatoire pour le nom -->

        <label for="theme">Thème :</label> <!-- Étiquette pour la sélection du thème -->
        <select id="theme" name="theme"> <!-- Liste déroulante pour choisir le thème -->
            <option value="dark">Sombre</option>
            <option value="light">Clair</option>
        </select>

        <label for="text_color">Couleur du Texte :</label> <!-- Étiquette pour la couleur -->
        <!-- Champ de type couleur avec une valeur par défaut (#00bcd4 = cyan) -->
        <input type="color" id="text_color" name="text_color" class="color-picker" value="#00bcd4">

        <div class="color-selection"> <!-- Conteneur pour le sélecteur et la prévisualisation -->
            <!-- Bouton pour ouvrir le sélecteur de couleur natif -->
            <button type="button" class="color-picker-button" onclick="document.getElementById('text_color').click()">Choisir une couleur</button>
            <!-- Élément vide pour afficher un aperçu de la couleur choisie (via JavaScript/CSS) -->
            <div id="color-preview"></div>
        </div>

        <!-- Champ caché pour indiquer au script PHP qu'il s'agit d'une sauvegarde -->
        <input type="hidden" name="save_preset" value="1">
        <!-- Bouton pour tester les préférences sans sauvegarder (géré via JavaScript) -->
        <button type="button" id="apply-button" class="btn">Test</button>
        <!-- Bouton pour soumettre le formulaire et sauvegarder le preset -->
        <button type="submit" class="btn">Sauvegarder</button>
    </form>
</div>

<?php
// Inclut le footer pour ajouter des éléments de fin de page (ex. scripts, navigation)
include BASE_PATH . 'includes/footer.php';
?>
</body>
</html>