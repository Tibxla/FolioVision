<?php
// Définit l'en-tête de la réponse comme JSON pour une communication avec le front-end
header('Content-Type: application/json');

// Récupère les données envoyées via POST (formulaire de contact)
$name = $_POST['name'] ?? ''; // Nom de l'expéditeur
$email = $_POST['email'] ?? ''; // Email de l'expéditeur
$message = $_POST['message'] ?? ''; // Message envoyé

// Vérifie que tous les champs sont remplis (non vides)
if (!empty($name) && !empty($email) && !empty($message)) {
    // Si tous les champs sont présents, renvoie un succès
    // Note : Aucune action réelle n'est effectuée ici (ex. envoi d'email ou enregistrement)
    echo json_encode(['success' => true]);
} else {
    // Si un champ est vide, renvoie une erreur avec un message
    echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
}