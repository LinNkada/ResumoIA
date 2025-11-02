<?php
// Conexão com MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resumos_ia"; // nome correto do banco

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

?>
