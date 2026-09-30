<?php
session_start();
require "conexion.php";
require "confg.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Generamos un código de 6 dígitos
        $codigo = strval(random_int(100000, 999999));
        $expira = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        $stmt = $pdo->prepare("INSERT INTO recuperacion_password (email, codigo, expira) VALUES (?, ?, ?)");
        $stmt->execute([$email, $codigo, $expira]);

        $enviado = enviarCodigoRecuperacion($email, $codigo);

        if ($enviado) {
            $_SESSION["recuperacion_email"] = $email;
            header("Location: verificar_codigo.php");
            exit;
        } else {
            $error = "No pudimos enviar el correo. Intentá de nuevo en unos minutos.";
        }
    } else {
        // No decimos si el mail existe o no, por seguridad
        $error = "Si el correo está registrado, vas a recibir un código.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McPapas - Recuperar contraseña</title>
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
                Recuperá el acceso<br>
                a tu cuenta 🍟
            </p>
            <div class="papas">
                <div class="papas-fritas">🍟</div>
                <div class="caja-papas">McPapas</div>
            </div>
        </div>

        <div class="lado-derecho">

            <div class="icono-login">
                <i class="fa-solid fa-key"></i>
            </div>

            <h1>¿Olvidaste tu</h1>
            <h2>contraseña?</h2>

            <p class="subtitulo">
                Ingresá tu correo y te mandamos un código para recuperarla
            </p>

            <?php if ($error): ?>
                <p style="color:red; text-align:center; margin-bottom:15px;">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endif; ?>

            <form action="recuperar.php" method="POST">

                <div class="campo">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" placeholder="Correo electrónico" required>
                </div>

                <button type="submit">
                    ENVIAR CÓDIGO
                </button>

            </form>

            <p class="registro">
                <a href="index.php">Volver a iniciar sesión</a>
            </p>

        </div>

    </div>

</body>

</html>