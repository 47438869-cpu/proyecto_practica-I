<?php
session_start();
require "conexion.php";

if (!isset($_SESSION["recuperacion_email"]) || !isset($_SESSION["recuperacion_verificada"])) {
    header("Location: recuperar.php");
    exit;
}

$email = $_SESSION["recuperacion_email"];
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password  = $_POST["password"];
    $password2 = $_POST["password2"];

    if ($password !== $password2) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE email = ?");
        $stmt->execute([$passwordHash, $email]);

        // Marcamos el código como usado para que no se pueda reutilizar
        $stmt = $pdo->prepare("UPDATE recuperacion_password SET usado = 1 WHERE id = ?");
        $stmt->execute([$_SESSION["recuperacion_id"]]);

        // Limpiamos la sesión de recuperación
        unset($_SESSION["recuperacion_email"]);
        unset($_SESSION["recuperacion_verificada"]);
        unset($_SESSION["recuperacion_id"]);

        header("Location: index.php?clave=ok");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McPapas - Nueva contraseña</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="icon" href="favicon.ico">
    <link rel="icon" type="image/png" href="favicon-32x32.png">
</head>

<body>

    <div class="contenedor">

        <div class="lado-izquierdo">
            <div class="logo"><span>Mc</span>Papas</div>
            <p class="texto-logo">
                Elegí una nueva<br>
                contraseña 🍟
            </p>
            <div class="papas">
                <div class="papas-fritas">🍟</div>
                <div class="caja-papas">McPapas</div>
            </div>
        </div>

        <div class="lado-derecho">

            <div class="icono-login">
                <i class="fa-solid fa-lock"></i>
            </div>

            <h1>Nueva</h1>
            <h2>contraseña</h2>

            <p class="subtitulo">
                Elegí una contraseña nueva para tu cuenta
            </p>

            <?php if ($error): ?>
                <p style="color:red; text-align:center; margin-bottom:15px;">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endif; ?>

            <form action="nueva_clave.php" method="POST">

                <div class="campo">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Nueva contraseña" required>
                </div>

                <div class="campo">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password2" placeholder="Confirmar contraseña" required>
                </div>

                <button type="submit">
                    GUARDAR CONTRASEÑA
                </button>

            </form>

        </div>

    </div>

</body>

</html>