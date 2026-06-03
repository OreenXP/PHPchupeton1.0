<?php
session_start();
require __DIR__ . '/config/db.php';

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['correo'] ?? '');
    $tel = trim($_POST['telefono'] ?? '');
    $pass = trim($_POST['password'] ?? '');
    $pass_confirm = trim($_POST['password_confirm'] ?? '');

    if ($user === '' || $email === '' || $tel === '' || $pass === '' || $pass_confirm === '') {
        $error = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo electrónico no es válido.';
    } elseif (!preg_match('/^[0-9\+\-\(\)\s]{7,20}$/', $tel)) {
        $error = 'El teléfono no es válido.';
    } elseif ($pass !== $pass_confirm) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (strlen($pass) < 4) {
        $error = 'La contraseña debe tener al menos 4 caracteres.';
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT usuario, correo FROM usuarios WHERE usuario = ? OR correo = ?");
        $stmt->execute([$user, $email]);
        $existente = $stmt->fetch();

        if ($existente) {
            if ($existente['usuario'] === $user) {
                $error = 'El usuario ya existe.';
            } else {
                $error = 'El correo ya está registrado.';
            }
        } else {
            $stmt = $db->prepare("INSERT INTO usuarios (usuario, correo, telefono, password, creado) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$user, $email, $tel, password_hash($pass, PASSWORD_DEFAULT)]);
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
                    <input type="text" name="usuario" required placeholder="Elige un usuario" minlength="3">
                </label>
                <label>
                    Correo electrónico:
                    <input type="email" name="correo" required placeholder="correo@ejemplo.com">
                </label>
                <label>
                    Teléfono:
                    <input type="tel" name="telefono" required placeholder="+52 123 456 7890">
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
