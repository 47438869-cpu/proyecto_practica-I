<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

require "conexion.php";

$periodo = $_GET["periodo"] ?? "dia";

switch ($periodo) {
    case "semana":
        $condicion = "YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)";
        $titulo_periodo = "esta semana";
        break;
    case "mes":
        $condicion = "YEAR(fecha) = YEAR(CURDATE()) AND MONTH(fecha) = MONTH(CURDATE())";
        $titulo_periodo = "este mes";
        break;
    default:
        $periodo = "dia";
        $condicion = "DATE(fecha) = CURDATE()";
        $titulo_periodo = "hoy";
        break;
}

$sqlMetodos = "SELECT
                metodo_pago,
                COALESCE(SUM(CASE WHEN es_devolucion = 0 THEN total ELSE 0 END), 0) AS total
             FROM ventas
             WHERE $condicion
             GROUP BY metodo_pago";
$filas = $pdo->query($sqlMetodos)->fetchAll(PDO::FETCH_ASSOC);

$porMetodo = ["efectivo" => 0, "qr" => 0, "transferencia" => 0];
foreach ($filas as $fila) {
    $porMetodo[$fila["metodo_pago"]] = $fila["total"];
}

$sqlResumen = "SELECT
                COALESCE(SUM(CASE WHEN es_devolucion = 0 THEN total ELSE 0 END), 0) AS total_recaudado,
                COALESCE(SUM(CASE WHEN es_devolucion = 1 THEN total ELSE 0 END), 0) AS total_devoluciones
             FROM ventas WHERE $condicion";
$resumen = $pdo->query($sqlResumen)->fetch(PDO::FETCH_ASSOC);

$totalNeto = $resumen["total_recaudado"] - $resumen["total_devoluciones"];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McPapas - Control de caja</title>
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="panel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

    <div class="panel">

        <div class="sidebar">
            <div class="sidebar-logo"><span>Mc</span>Papas</div>

            <nav class="sidebar-menu">
                <a href="bienvenida.php"><i class="fa-solid fa-house"></i> Inicio</a>
                <a href="productos.php"><i class="fa-solid fa-burger"></i> Productos</a>
                <a href="ventas.php"><i class="fa-solid fa-chart-line"></i> Ventas</a>
                <a href="caja.php" class="activo"><i class="fa-solid fa-cash-register"></i> Control de caja</a>
                <a href="ajustes.php"><i class="fa-solid fa-gear"></i> Ajustes</a>
            </nav>

            <a href="logout.php" class="cerrar-sesion"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
        </div>

        <div class="contenido">

            <h1>Control de caja</h1>
            <p class="subtitulo-panel">Recaudación de <?= $titulo_periodo ?></p>

            <div class="filtros-periodo">
                <a href="?periodo=dia" class="<?= $periodo === 'dia' ? 'activo' : '' ?>">Día</a>
                <a href="?periodo=semana" class="<?= $periodo === 'semana' ? 'activo' : '' ?>">Semana</a>
                <a href="?periodo=mes" class="<?= $periodo === 'mes' ? 'activo' : '' ?>">Mes</a>
            </div>

            <div class="tarjetas">

                <div class="tarjeta tarjeta-destacada">
                    <i class="fa-solid fa-vault"></i>
                    <h3>Total neto en caja</h3>
                    <p class="valor">$<?= number_format($totalNeto, 2) ?></p>
                </div>

                <div class="tarjeta">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    <h3>Efectivo</h3>
                    <p class="valor">$<?= number_format($porMetodo['efectivo'], 2) ?></p>
                </div>

                <div class="tarjeta">
                    <i class="fa-solid fa-qrcode"></i>
                    <h3>QR</h3>
                    <p class="valor">$<?= number_format($porMetodo['qr'], 2) ?></p>
                </div>

                <div class="tarjeta">
                    <i class="fa-solid fa-building-columns"></i>
                    <h3>Transferencia</h3>
                    <p class="valor">$<?= number_format($porMetodo['transferencia'], 2) ?></p>
                </div>

                <div class="tarjeta">
                    <i class="fa-solid fa-rotate-left"></i>
                    <h3>Devoluciones</h3>
                    <p class="valor">-$<?= number_format($resumen['total_devoluciones'], 2) ?></p>
                </div>

            </div>

        </div>

    </div>

</body>

</html>