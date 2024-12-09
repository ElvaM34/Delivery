<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrativo</title>
    <link rel="stylesheet" href="css3/dashboard_admin.css">
</head>
<body>
    <div class="navbar">
        <h1>Panel Administrativo</h1>
        <a href="logout.php" class="logout-button">Cerrar Sesion</a>
    </div>
    <div class="container">
    <h2>Bienvenido, Al dashboard de administradores</h2>
    <p>Selecciona una de las opciones para gestionar el sistema:</p>

        <div class="card-container">
            <a href="gestion_pedidos.php" class="card">
                <h3>Gestion de Pedidos</h3>
                <p>Administra los pedidos realizados por los clientes.</p>
            </a>
            <a href="gestion_repartidores.php" class="card">
                <h3>Gestion de Repartidores</h3>
                <p>Controla los repartidores y sus asignaciones.</p>
            </a>
            <a href="gestion_clientes.php" class="card">
                <h3>Gestion de Clientes</h3>
                <p>Consulta y administra la informacion de los clientes.</p>
            </a>
            <a href="gestion_restaurantes.php" class="card">
                <h3>Gestion de Restaurantes</h3>
                <p>Administra los restaurantes asociados al sistema</p>
            </a>
        </div>
    </div>
</body>
</html>
