<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bloc de Notas</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header>
    <h1>Bloc de Notas</h1>
    <p>Usuario: <?= htmlspecialchars($_SESSION['user']['username']) ?> | <a href="/logout.php">Cerrar sesión</a></p>
</header>
<main>
