<?php
$host = 'db.multipass';
$user = 'apcrud';
$pass = 'senha123';
$db   = 'product';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Erro de conexão: " . mysqli_connect_error());
}

echo "Conectado com sucesso!";
?>
