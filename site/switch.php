<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Connexion à MySQL
$mysqli = new mysqli("mysql", "user", "user_password", "users_db");

// Récupérer l'ID Token depuis l'en-tête
$idToken = $_SERVER['HTTP_OIDC_ID_TOKEN'] ?? null;

if (!$idToken) {
    http_response_code(401);
    echo "Token manquant.";
    exit();
}

// Clé publique Keycloak
$publicKey = <<<EOD
-----BEGIN PUBLIC KEY-----
<Votre clé publique ici>
-----END PUBLIC KEY-----
EOD;

try {
    $decoded = JWT::decode($idToken, new Key($publicKey, 'RS256'));
    $email = $decoded->email ?? null;
    $exp = $decoded->exp ?? null; // Expiration UNIX du token

    if (!$email || !$exp) {
        http_response_code(401);
        echo "Email ou expiration manquants dans le token.";
        exit();
    }

    // Générer une clé unique
    $authKey = bin2hex(random_bytes(32));
    $expiration = date('Y-m-d H:i:s', $exp); // Convertir UNIX en timestamp SQL

    // Enregistrer ou mettre à jour la clé et son expiration
    $stmt = $mysqli->prepare("UPDATE users SET auth_key = ?, key_expiration = ? WHERE email = ?");
    $stmt->bind_param("sss", $authKey, $expiration, $email);
    $stmt->execute();
    $stmt->close();

    // Transmettre la clé au client
    header("X-Auth-Key: $authKey");
    header("Location: /");
    exit();

} catch (Exception $e) {
    http_response_code(401);
    echo "Erreur lors de la validation du token : " . $e->getMessage();
    exit();
}
?>
