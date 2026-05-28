<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - FidelX</title>
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
                <li><a href="logout.php" class="active">Cerrar Sesión</a></li>
            </ul>
        </nav>
        <button id="modo-oscuro" class="modo-btn" type="button">☾</button>
    </header>

    <main>
        <section>
            <h2>Panel de Administración</h2>
            <p>Bienvenido, <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong>.</p>
            <p>Sesión iniciada el: <?= $_SESSION['inicio_sesion'] ?></p>
            <br>
            <a href="logout.php" class="button">Cerrar Sesión</a>
        </section>
    </main>

    <hr>
    <footer>2026 FidelX - Todos los derechos reservados</footer>
    <script src="js/tema.js"></script>
</body>
</html>
