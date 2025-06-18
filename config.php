<?php
/* Database credentials */
define('DB_SERVER', '172.17.0.3');
define('DB_USERNAME', 'hadiouser');
define('DB_PASSWORD', 'user123');
define('DB_NAME', 'hadiodb');

try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // Ativar o modo de erros do PDO para exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>
