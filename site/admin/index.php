<?php
require '../middleware.php';

$mysqli = new mysqli("mysql", "user", "user_password", "users_db");

// Vérifier la clé et récupérer l'utilisateur
$user = checkAuthKey($mysqli);

if ($user['role'] !== 'admin') {
    http_response_code(403);
    echo "Accès interdit : Vous n'avez pas les permissions nécessaires.";
    exit();
}

echo "Bienvenue dans l'espace admin.";
?>
