<?php
require "../conexion.php";

$carpetaImagenes = "../uploads/productos/";

$productos = $pdo->query("SELECT * FROM productos ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="productos.css">
    <title>mc_papas</title>
</head>
<body>
    <!--------------------------------------Inicio------------------------------------>
    <nav class="barra-superior">
        <div class="logo">
            🍟MC PAPAS
            <input type="checkbox" id="menu-toggle">
            <label for="menu-toggle" class="menu-icon">☰</label>
        </div>
        <div class="opciones">
            <a href="inicio.html">Inicio</a>
            <a href="productos.php">Productos</a>
            <a href="nosotros.html">Nosotros</a>
        </div>
    </nav>

        <!---------------------APARTADO-CATEGORIAS----------------->
        <section class="categorias">
            <h2>CATEGORIAS</h2>
            <div class="contenedor-categorias">

                <!------------MENU---------->
                <div class="categoria">
                    <div class="icono">
                        <img src="papas.svg" alt="Menu">
                    </div>
                    <p>COMIDA</p>
                </div>

                <!-----------BEBIDAS-------->
                 <div class="categoria">
                    <div class="icono">
                        <img src="bebida.svg" alt="Bebida">
                    </div>
                    <p>BEBIDAS</p>
                </div>

                <!-----------ADEREZOS------->
                 <div class="categoria">
                    <div class="icono">
                        <img src="salsas.svg" alt="Aderezo">
                    </div>
                    <p>ADEREZOS</p>
                </div>
            </div>
        </section>
        <!---------------------APARTADO-PRODUCTOS----------------->
        <section class="productos">

            <?php if (empty($productos)): ?>

                <p style="color:white; text-align:center; width:100%;">
                    Todavía no hay productos cargados.
                </p>

            <?php else: ?>

                <?php foreach ($productos as $p): ?>
                    <div class="producto">
                        <?php if (!empty($p['imagen'])): ?>
                            <img src="<?= $carpetaImagenes . htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>">
                        <?php else: ?>
                            <img src="sin-imagen.jpg" alt="<?= htmlspecialchars($p['nombre']) ?>">
                        <?php endif; ?>
                        <div class="info-producto">
                            <h4><?= htmlspecialchars($p['nombre']) ?></h4>
                            <p><?= htmlspecialchars($p['descripcion']) ?></p>
                            <span>$<?= number_format($p['precio'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>

        </section>

    <!------------------- informacion / contacto ----------------->
        <section class="info-section" id="contacto">
        <div class="info-grid">
            <div class="info-box">
                <h4>Encuéntranos</h4>
                <p>📍 Av. Principal #123, Centro de la Ciudad</p>
                <p>📞 Teléfono: +54 9 3886 567893</p>
                <p>✉️ info@fastfood.com</p>
            </div>
            <div class="info-box">
                <h4>Horarios de Atención</h4>
                <p>Lunes a Jueves: 17:00 PM - 02:00 AM</p>
                <p>Viernes a Domingo: 17:00 PM - 03:00 AM</p>
            </div>
            <div class="info-box">
                <h4>¡Pide Delivery!</h4>
                <p>Hacemos envíos rápidos a todo el centro de la ciudad de forma directa o por apps asociadas.</p>
            </div>
        </div>
    </section>
    <!----------- pie de pagina ----------->
     <footer>
        <p>© 2026 FastFood. Creado para amantes de la buena comida.</p>
    </footer>
</body>
</html>