<?php
session_start();
require "conexion.php";

if (!isset($_SESSION["recuperacion_email"])) {
    header("Location: recuperar.php");
    exit;
}

$email = $_SESSION["recuperacion_email"];
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigoIngresado = trim($_POST["codigo"]);

    $stmt = $pdo->prepare("
        SELECT * FROM recuperacion_password
        WHERE email = ? AND codigo = ? AND usado = 0
        ORDER BY id DESC LIMIT 1
    ");
    $stmt->execute([$email, $codigoIngresado]);
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$registro) {
        $error = "El código ingresado es incorrecto.";
    } elseif (strtotime($registro["expira"]) < time()) {
        $error = "El código venció. Pedí uno nuevo.";
    } else {
        // Código válido: marcamos que este mail quedó verificado
        $_SESSION["recuperacion_verificada"] = true;
        $_SESSION["recuperacion_id"] = $registro["id"];

        header("Location: nueva-clave.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McPapas - Verificar código</title>
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
                Ya casi estás<br>
                adentro 🍟
            </p>
            <div class="papas">
                <div class="papas-fritas">🍟</div>
                <div class="caja-papas">McPapas</div>
            </div>
        </div>

        <div class="lado-derecho">

            <div class="icono-login">
                <i class="fa-solid fa-shield-halved"></i>
            </div>

            <h1>Ingresá el</h1>
            <h2>código</h2>

            <p class="subtitulo">
                Te lo mandamos a <?= htmlspecialchars($email) ?>
            </p>

            <?php if ($error): ?>
                <p style="color:red; text-align:center; margin-bottom:15px;">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endif; ?>

            <form action="verificar_codigo.php" method="POST">

                <div class="campo">
                    <i class="fa-solid fa-key"></i>
                    <input type="text" name="codigo" placeholder="Código de 6 dígitos" maxlength="6" required>
                </div>

                <button type="submit">
                    VERIFICAR
                </button>

            </form>

            <p class="registro">
                ¿No te llegó? <a href="recuperar.php">Pedir otro código</a>
            </p>

        </div>

    </div>

</body>

</html>