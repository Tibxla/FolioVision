<?php
// Définit une constante pour le chemin absolu vers la racine de l’application
// dirname(__DIR__) remonte au dossier parent du fichier actuel, puis ajoute un slash
define('BASE_PATH', dirname(__DIR__) . '/');

// Définit une constante pour l’URL de base, utilisée dans les liens HTML
// '/FolioVision/' correspond au chemin virtuel configuré sur le serveur web
define('BASE_URL', '/FolioVision/');
