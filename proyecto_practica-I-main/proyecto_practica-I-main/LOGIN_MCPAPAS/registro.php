<?php
require "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $email  = trim($_POST["email"]);
    $password = $_POST["password"];

    // Hasheamos la contraseña, nunca se guarda en texto plano
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $email, $passwordHash]);

        header("Location: index.php?registro=ok");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error = "Ese correo ya está registrado.";
        } else {
            $error = "Error al registrar: " . $e->getMessage();
        }
    }
}
?>
<?php
session_start();
require "conexion.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre   = trim($_POST["nombre"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    if ($password !== $password2) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$nombre, $email, $passwordHash]);

            header("Location: index.php?registro=ok");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Ese correo ya está registrado.";
            } else {
                $error = "Error al registrar: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>McPapas - Registro de administrador</title>

    <link rel="stylesheet" href="style.css">

    <!-- Iconos -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="icon" href="favicon.ico">
       <link rel="icon" type="image/png" href="favicon-32x32.png">
</head>

<body>

    <div class="contenedor">

        <!-- =========================
             LADO IZQUIERDO
        ========================== -->

        <div class="lado-izquierdo">

            <div class="logo">
                <span>Mc</span>Papas
            </div>

            <p class="texto-logo">
                Panel de administración<br>
                🍟
            </p>

            <!-- Papas -->
            <div class="papas">

                <div class="papas-fritas">
                    🍟
                </div>

                <div class="caja-papas">
                    McPapas
                </div>

            </div>

        </div>


        <!-- =========================
             LADO DERECHO
        ========================== -->

        <div class="lado-derecho">

            <!-- Icono -->
            <div class="icono-login">
                <i class="fa-solid fa-user-plus"></i>
            </div>

            <h1>Registrate como</h1>

            <h2>usuario</h2>

            <p class="subtitulo">
                Completá tus datos para crear tu cuenta
            </p>

            <?php if ($error): ?>
                <p style="color:red; text-align:center; margin-bottom:15px;">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endif; ?>

            <!-- FORMULARIO -->

            <form action="registro.php" method="POST">

                <!-- NOMBRE -->

                <div class="campo">

                    <i class="fa-solid fa-user"></i>

                    <input
                        type="text"
                        name="nombre"
                        placeholder="Nombre completo"
                        required>

                </div>


                <!-- EMAIL -->

                <div class="campo">

                    <i class="fa-solid fa-envelope"></i>

                    <input
                        type="email"
                        name="email"
                        placeholder="Correo electrónico"
                        required>

                </div>


                <!-- CONTRASEÑA -->

                <div class="campo">

                    <i class="fa-solid fa-lock"></i>

                    <input
                        type="password"
                        name="password"
                        placeholder="Contraseña"
                        required>

                    <i class="fa-solid fa-eye ojo"></i>

                </div>


                <!-- CONFIRMAR CONTRASEÑA -->

                <div class="campo">

                    <i class="fa-solid fa-lock"></i>

                    <input
                        type="password"
                        name="password2"
                        placeholder="Confirmar contraseña"
                        required>

                    <i class="fa-solid fa-eye ojo"></i>

                </div>


                <!-- BOTÓN -->

                <button type="submit">
                    REGISTRARME
                </button>

            </form>


            <!-- LOGIN -->

            <p class="registro">

                ¿Ya tenés una cuenta?

                <a href="index.php">
                    Iniciar sesión
                </a>

            </p>

        </div>

    </div>

</body>

</html>