<?php
session_start();
require 'config/db.php';
if (\$_SERVER['REQUEST_METHOD']==='POST') {
    \$user = \$_POST['username'];
    \$pass = \$_POST['password'];
    \$stmt = \$pdo->prepare('SELECT * FROM users WHERE username=?');
    \$stmt->execute([\$user]);
    \$u = \$stmt->fetch();
    if (\$u && password_verify(\$pass, \$u['password'])) {
        \$_SESSION['user'] = ['id'=>\$u['id'], 'username'=>\$u['username'], 'role'=>\$u['role']];
        if (\$u['role']==='Administrador') header('Location: /administrador/users.php');
        else header('Location: /recepcionista/notes.php');
        exit;
    } else {
        \$error = 'Credenciales inválidas';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Login</title></head>
<body>
<h2>Login</h2>
<?php if (!empty(\$error)): ?><p style="color:red"><?= \$error ?></p><?php endif; ?>
<form method="post">
    <label>Usuario: <input name="username" required></label><br>
    <label>Contraseña: <input type="password" name="password" required></label><br>
    <button type="submit">Entrar</button>
</form>
</body>
</html>
