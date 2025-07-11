<?php
session_start();
require 'config/db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, username, password, role FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Guardar datos de sesión
        $_SESSION['user'] = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'role'     => $user['role'],
        ];
        // Redirigir según rol (a definir en futuras peticiones)
        header('Location: /');
        exit;
    } else {
        $error = 'Credenciales inválidas';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Bloc de Notas</title>
</head>
<body>
    <h2>Iniciar sesión</h2>
    <?php if ($error): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Usuario:<input type="text" name="username" required></label><br>
        <label>Contraseña:<input type="password" name="password" required></label><br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>


// logout.php
