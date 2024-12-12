<?php
function checkAuthKey($mysqli) {
    $authKey = $_SERVER['HTTP_X_AUTH_KEY'] ?? null;

    if (!$authKey) {
        http_response_code(401);
        echo "Clé d'authentification manquante.";
        exit();
    }

    $stmt = $mysqli->prepare("SELECT email, role, key_expiration FROM users WHERE auth_key = ?");
    $stmt->bind_param("s", $authKey);
    $stmt->execute();
    $stmt->bind_result($email, $role, $keyExpiration);
    $stmt->fetch();
    $stmt->close();

    if (!$email || !$keyExpiration) {
        http_response_code(403);
        echo "Clé invalide.";
        exit();
    }

    // Vérifier l'expiration de la clé
    $now = new DateTime();
    $expiration = new DateTime($keyExpiration);

    if ($expiration < $now) {
        http_response_code(401);
        echo "Clé expirée.";
        exit();
    }

    return ['email' => $email, 'role' => $role];
}
?>
