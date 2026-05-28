<?php
session_start();
$error = '';
$exito = '';

$archivo_usuarios = __DIR__ . '/usuarios.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['usuario'] ?? '');
    $pass = trim($_POST['password'] ?? '');
    $pass_confirm = trim($_POST['password_confirm'] ?? '');

    if ($user === '' || $pass === '' || $pass_confirm === '') {
        $error = 'Todos los campos son obligatorios.';
    } elseif ($pass !== $pass_confirm) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (strlen($pass) < 4) {
        $error = 'La contraseña debe tener al menos 4 caracteres.';
    } else {
        $usuarios = [];
        if (file_exists($archivo_usuarios)) {
            $usuarios = json_decode(file_get_contents($archivo_usuarios), true) ?? [];
        }

        foreach ($usuarios as $u) {
            if ($u['usuario'] === $user) {
                $error = 'El usuario ya existe.';
                break;
            }
        }

        if (!$error) {
            $usuarios[] = [
                'usuario' => $user,
                'password' => password_hash($pass, PASSWORD_DEFAULT),
                'creado' => date('Y-m-d H:i:s'),
            ];
            file_put_contents($archivo_usuarios, json_encode($usuarios, JSON_PRETTY_PRINT));
            $exito = 'Cuenta creada correctamente. <a href="login.php">Iniciar sesión</a>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - FidelX</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>FidelX</h1>
        <nav>
            <ul>
                <li><a href="html/index.html">Inicio</a></li>
                <li><a href="html/servicios.html">Servicios</a></li>
                <li><a href="html/acercanosotros.html">Acerca de Nosotros</a></li>
                <li><a href="html/instalaciones.html">Instalaciones</a></li>
                <li><a href="html/contacto.html">Contacto</a></li>
                <li><a href="login.php">Iniciar Sesión</a></li>
            </ul>
        </nav>
        <button id="modo-oscuro" class="modo-btn" type="button">☾</button>
    </header>

    <main>
        <section class="login-section">
            <h2>Crear Cuenta</h2>
            <?php if ($error): ?>
                <div class="error-msg"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($exito): ?>
                <div class="success-msg"><?= $exito ?></div>
            <?php endif; ?>
            <form method="POST" class="login-form">
                <label>
                    Usuario:
                    <input type="text" name="usuario" required placeholder="Elige un usuario">
                </label>
                <label>
                    Contraseña:
                    <input type="password" name="password" required placeholder="Mínimo 4 caracteres">
                </label>
                <label>
                    Confirmar Contraseña:
                    <input type="password" name="password_confirm" required placeholder="Repite la contraseña">
                </label>
                <button type="submit">Crear Cuenta</button>
            </form>
            <p style="margin-top:15px;text-align:center;">
                ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
            </p>
        </section>
    </main>

    <hr>
    <footer>2026 FidelX - Todos los derechos reservados</footer>
    <script src="js/tema.js"></script>
</body>
</html>
