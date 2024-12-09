<?php
$host = '127.0.0.1';
$user = 'root';
$password = 'Camacho26@';   
$database = 'osonny_db';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexion fallida: " . $conn->connect_error);
}
?>
