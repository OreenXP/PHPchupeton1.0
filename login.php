<?php
session_start();
$error = '';

$archivo_usuarios = __DIR__ . '/usuarios.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['usuario'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    if (file_exists($archivo_usuarios)) {
        $usuarios = json_decode(file_get_contents($archivo_usuarios), true) ?? [];
        foreach ($usuarios as $u) {
            if ($u['usuario'] === $user && password_verify($pass, $u['password'])) {
                $_SESSION['usuario'] = $user;
                $_SESSION['inicio_sesion'] = date('Y-m-d H:i:s');
                header('Location: panel.php');
                exit;
            }
        }
    }
    $error = 'Usuario o contraseña incorrectos.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - FidelX</title>
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
                <li><a href="registro.php">Registrarse</a></li>
            </ul>
        </nav>
        <button id="modo-oscuro" class="modo-btn" type="button">☾</button>
    </header>

    <main>
        <section class="login-section">
            <h2>Iniciar Sesión</h2>
            <?php if ($error): ?>
                <div class="error-msg"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" class="login-form">
                <label>
                    Usuario:
                    <input type="text" name="usuario" required placeholder="admin">
                </label>
                <label>
                    Contraseña:
                    <input type="password" name="password" required placeholder="1234">
                </label>
                <button type="submit">Entrar</button>
            </form>
            <p style="margin-top:15px;text-align:center;">
                ¿No tienes cuenta? <a href="registro.php">Regístrate</a>
            </p>
        </section>
    </main>

    <hr>
    <footer>2026 FidelX - Todos los derechos reservados</footer>
    <script src="js/tema.js"></script>
</body>
</html>
