<?php session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /login.php'); exit;
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
    <p>Hola, <?= htmlspecialchars(\$_SESSION['user']['username']) ?> | <a href="/logout.php">Salir</a></p>
    <nav>
        <?php if (\$_SESSION['user']['role']==='Administrador'): ?>
            <a href="/administrador/users.php">Usuarios</a>
        <?php endif; ?>
        <?php if (\$_SESSION['user']['role']==='Recepcionista'): ?>
            <a href="/recepcionista/notes.php">Notas</a>
        <?php endif; ?>
    </nav>
</header>
<main>
