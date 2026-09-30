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
 
$sqlTotal = "SELECT
                COALESCE(SUM(CASE WHEN es_devolucion = 0 THEN total ELSE 0 END), 0) AS total_ventas,
                COALESCE(SUM(CASE WHEN es_devolucion = 1 THEN total ELSE 0 END), 0) AS total_devoluciones,
                COUNT(CASE WHEN es_devolucion = 0 THEN 1 END) AS cantidad_ventas
             FROM ventas WHERE $condicion";
$stmt = $pdo->query($sqlTotal);
$resumen = $stmt->fetch(PDO::FETCH_ASSOC);
 
$sqlDetalle = "SELECT * FROM ventas WHERE $condicion ORDER BY fecha DESC";
$detalle = $pdo->query($sqlDetalle)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McPapas - Ventas</title>
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
                <a href="ventas.php" class="activo"><i class="fa-solid fa-chart-line"></i> Ventas</a>
                <a href="caja.php"><i class="fa-solid fa-cash-register"></i> Control de caja</a>
                <a href="ajustes.php"><i class="fa-solid fa-gear"></i> Ajustes</a>
            </nav>
 
            <a href="logout.php" class="cerrar-sesion"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
        </div>
 
        <div class="contenido">
 
            <h1>Ventas</h1>
            <p class="subtitulo-panel">Resumen de <?= $titulo_periodo ?></p>
 
            <div class="filtros-periodo">
                <a href="?periodo=dia" class="<?= $periodo === 'dia' ? 'activo' : '' ?>">Día</a>
                <a href="?periodo=semana" class="<?= $periodo === 'semana' ? 'activo' : '' ?>">Semana</a>
                <a href="?periodo=mes" class="<?= $periodo === 'mes' ? 'activo' : '' ?>">Mes</a>
            </div>
 
            <div class="tarjetas">
 
                <div class="tarjeta">
                    <i class="fa-solid fa-sack-dollar"></i>
                    <h3>Total vendido</h3>
                    <p class="valor">$<?= number_format($resumen['total_ventas'], 2) ?></p>
                </div>
 
                <div class="tarjeta">
                    <i class="fa-solid fa-receipt"></i>
                    <h3>Cantidad de ventas</h3>
                    <p class="valor"><?= $resumen['cantidad_ventas'] ?></p>
                </div>
 
                <div class="tarjeta">
                    <i class="fa-solid fa-rotate-left"></i>
                    <h3>Devoluciones</h3>
                    <p class="valor">$<?= number_format($resumen['total_devoluciones'], 2) ?></p>
                </div>
 
            </div>
 
            <h2 class="titulo-tabla">Detalle</h2>
 
            <table class="tabla-datos">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Cant.</th>
                        <th>Método de pago</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($detalle)): ?>
                        <tr><td colspan="6" class="sin-datos">No hay ventas registradas en este período.</td></tr>
                    <?php else: ?>
                        <?php foreach ($detalle as $venta): ?>
                            <tr>
                                <td><?= date("d/m/Y H:i", strtotime($venta['fecha'])) ?></td>
                                <td><?= htmlspecialchars($venta['producto']) ?></td>
                                <td><?= $venta['cantidad'] ?></td>
                                <td><?= ucfirst($venta['metodo_pago']) ?></td>
                                <td>$<?= number_format($venta['total'], 2) ?></td>
                                <td>
                                    <?php if ($venta['es_devolucion']): ?>
                                        <span class="etiqueta etiqueta-roja">Devolución</span>
                                    <?php else: ?>
                                        <span class="etiqueta etiqueta-verde">Venta</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
 
        </div>
 
    </div>
 
</body>
 
</html>
 