<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>McPapas - Panel de administración</title>

    <link rel="stylesheet" href="panel.css">

    <!-- Iconos -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link rel="icon" href="favicon.ico">
           <link rel="icon" type="image/png" href="favicon-32x32.png">
    </head>

<body>

    <div class="panel">

        <!-- =========================
             MENÚ LATERAL
        ========================== -->

        <div class="sidebar">

            <div class="sidebar-logo">
                <span>Mc</span>Papas
            </div>

            <nav class="sidebar-menu">

                <a href="bienvenida.php" class="activo">
                    <i class="fa-solid fa-house"></i>
                    Inicio
                </a>

                <a href="productos.php">
                    <i class="fa-solid fa-burger"></i>
                    Productos
                </a>

                <a href="ventas.php">
                    <i class="fa-solid fa-chart-line"></i>
                    Ventas
                </a>

                <a href="caja.php">
                    <i class="fa-solid fa-cash-register"></i>
                    Control de caja
                </a>

                <a href="#">
                    <i class="fa-solid fa-gear"></i>
                    Ajustes
                </a>

            </nav>

            <a href="logout.php" class="cerrar-sesion">
                <i class="fa-solid fa-right-from-bracket"></i>
                Cerrar sesión
            </a>

        </div>


        <!-- =========================
             CONTENIDO
        ========================== -->

        <div class="contenido">

            <h1>¡Bienvenido, <?= htmlspecialchars($_SESSION["usuario_nombre"]) ?>!</h1>

            <p class="subtitulo-panel">
                Este es el resumen de tu local hoy.
            </p>

           

</body>

</html>