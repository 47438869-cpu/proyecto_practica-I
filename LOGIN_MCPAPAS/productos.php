<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

require "conexion.php";

$carpetaImagenes = "uploads/productos/";

// =========================
// AGREGAR PRODUCTO
// =========================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "agregar") {
    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio = $_POST["precio"];
    $nombreImagen = null;

    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
        if (!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes, 0777, true);
        }

        $extension = strtolower(pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION));
        $extensionesPermitidas = ["jpg", "jpeg", "png", "webp", "gif"];

        if (in_array($extension, $extensionesPermitidas)) {
            $nombreImagen = uniqid("prod_") . "." . $extension;
            move_uploaded_file($_FILES["imagen"]["tmp_name"], $carpetaImagenes . $nombreImagen);
        }
    }

    $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, imagen, precio) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $descripcion, $nombreImagen, $precio]);

    header("Location: productos.php?ok=agregado");
    exit;
}

// =========================
// EDITAR PRODUCTO
// =========================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "editar") {
    $id = $_POST["id"];
    $descripcion = trim($_POST["descripcion"]);
    $precio = $_POST["precio"];

    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
        if (!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes, 0777, true);
        }

        $extension = strtolower(pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION));
        $extensionesPermitidas = ["jpg", "jpeg", "png", "webp", "gif"];

        if (in_array($extension, $extensionesPermitidas)) {
            $nombreImagen = uniqid("prod_") . "." . $extension;
            move_uploaded_file($_FILES["imagen"]["tmp_name"], $carpetaImagenes . $nombreImagen);

            $stmt = $pdo->prepare("UPDATE productos SET descripcion = ?, precio = ?, imagen = ? WHERE id = ?");
            $stmt->execute([$descripcion, $precio, $nombreImagen, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE productos SET descripcion = ?, precio = ? WHERE id = ?");
            $stmt->execute([$descripcion, $precio, $id]);
        }
    } else {
        $stmt = $pdo->prepare("UPDATE productos SET descripcion = ?, precio = ? WHERE id = ?");
        $stmt->execute([$descripcion, $precio, $id]);
    }

    header("Location: productos.php?ok=editado");
    exit;
}

// =========================
// ELIMINAR PRODUCTO
// =========================
if (isset($_GET["eliminar"])) {
    $id = $_GET["eliminar"];

    $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: productos.php?ok=eliminado");
    exit;
}

$productos = $pdo->query("SELECT * FROM productos ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McPapas - Productos</title>
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
                <a href="productos.php" class="activo"><i class="fa-solid fa-burger"></i> Productos</a>
                <a href="ventas.php"><i class="fa-solid fa-chart-line"></i> Ventas</a>
                <a href="caja.php"><i class="fa-solid fa-cash-register"></i> Control de caja</a>
                <a href="ajustes.php"><i class="fa-solid fa-gear"></i> Ajustes</a>
            </nav>

            <a href="logout.php" class="cerrar-sesion"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
        </div>

        <div class="contenido">

            <div class="encabezado-productos">
                <div>
                    <h1>Productos</h1>
                    <p class="subtitulo-panel">Agregá, editá o eliminá los productos del menú.</p>
                </div>

                <button class="boton-nuevo" onclick="document.getElementById('modal-agregar').style.display='flex'">
                    <i class="fa-solid fa-plus"></i> Nuevo producto
                </button>
            </div>

            <?php if (isset($_GET["ok"])): ?>
                <p class="mensaje-ok">
                    <?php
                        $mensajes = [
                            "agregado" => "Producto agregado correctamente.",
                            "editado" => "Producto actualizado correctamente.",
                            "eliminado" => "Producto eliminado correctamente."
                        ];
                        echo $mensajes[$_GET["ok"]] ?? "";
                    ?>
                </p>
            <?php endif; ?>

            <table class="tabla-datos">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productos)): ?>
                        <tr><td colspan="5" class="sin-datos">Todavía no cargaste productos.</td></tr>
                    <?php else: ?>
                        <?php foreach ($productos as $p): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($p['imagen'])): ?>
                                        <img class="miniatura-producto" src="<?= $carpetaImagenes . htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>">
                                    <?php else: ?>
                                        <div class="miniatura-vacia"><i class="fa-solid fa-image"></i></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($p['nombre']) ?></td>
                                <td><?= htmlspecialchars($p['descripcion']) ?></td>
                                <td>$<?= number_format($p['precio'], 2) ?></td>
                                <td class="acciones">
                                    <button class="btn-icono"
                                        onclick='abrirEditar(<?= json_encode($p) ?>, "<?= $carpetaImagenes ?>")'>
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <a class="btn-icono btn-eliminar"
                                       href="productos.php?eliminar=<?= $p['id'] ?>"
                                       onclick="return confirm('¿Seguro que querés eliminar este producto?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>

    </div>


    <!-- =========================
         MODAL AGREGAR
    ========================== -->

    <div class="modal-fondo" id="modal-agregar">
        <div class="modal-caja">

            <h2>Nuevo producto</h2>

            <form method="POST" action="productos.php" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="agregar">

                <label>Nombre</label>
                <input type="text" name="nombre" placeholder="Ej: Papas fritas grandes" required>

                <label>Descripción</label>
                <input type="text" name="descripcion" placeholder="Ej: Con cheddar y panceta">

                <label>Imagen del producto</label>
                <input type="file" name="imagen" accept="image/*">

                <label>Precio</label>
                <input type="number" step="0.01" name="precio" placeholder="Ej: 3500" required>

                <div class="modal-botones">
                    <button type="button" class="boton-cancelar" onclick="document.getElementById('modal-agregar').style.display='none'">Cancelar</button>
                    <button type="submit" class="boton-guardar">Guardar</button>
                </div>
            </form>

        </div>
    </div>


    <!-- =========================
         MODAL EDITAR
    ========================== -->

    <div class="modal-fondo" id="modal-editar">
        <div class="modal-caja">

            <h2>Editar producto</h2>
            <p class="modal-nombre-producto" id="editar-nombre"></p>

            <img id="editar-imagen-actual" class="miniatura-modal" src="" alt="">

            <form method="POST" action="productos.php" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="id" id="editar-id">

                <label>Descripción</label>
                <input type="text" name="descripcion" id="editar-descripcion">

                <label>Cambiar imagen (opcional)</label>
                <input type="file" name="imagen" accept="image/*">

                <label>Precio</label>
                <input type="number" step="0.01" name="precio" id="editar-precio" required>

                <div class="modal-botones">
                    <button type="button" class="boton-cancelar" onclick="document.getElementById('modal-editar').style.display='none'">Cancelar</button>
                    <button type="submit" class="boton-guardar">Guardar cambios</button>
                </div>
            </form>

        </div>
    </div>


    <script>
        function abrirEditar(producto, carpetaImagenes) {
            document.getElementById('editar-id').value = producto.id;
            document.getElementById('editar-nombre').innerText = producto.nombre;
            document.getElementById('editar-descripcion').value = producto.descripcion;
            document.getElementById('editar-precio').value = producto.precio;

            const img = document.getElementById('editar-imagen-actual');
            if (producto.imagen) {
                img.src = carpetaImagenes + producto.imagen;
                img.style.display = 'block';
            } else {
                img.style.display = 'none';
            }

            document.getElementById('modal-editar').style.display = 'flex';
        }
    </script>

</body>

</html>