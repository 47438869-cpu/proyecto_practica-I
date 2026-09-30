<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>McPapas - Iniciar sesión</title>

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
                ¡Las mejores papas<br>
                están acá! 🍟
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
                <i class="fa-solid fa-user"></i>
            </div>

            <h1>¡Bienvenido a</h1>

            <h2>McPapas!</h2>

            <p class="subtitulo">
                Iniciá sesión para continuar
            </p>


            <!-- FORMULARIO -->
             <?php if (isset($_GET["error"])): ?>
    <p style="color:red; text-align:center; margin-bottom:15px;">
        Correo o contraseña incorrectos.
    </p>
<?php endif; ?>
<?php if (isset($_GET["clave"]) && $_GET["clave"] === "ok"): ?>
    <p style="color:#1a8a4a; text-align:center; margin-bottom:15px;">
        Contraseña actualizada. Ya podés iniciar sesión.
    </p>
<?php endif; ?>
            <form action="login.php" method="POST">

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


                <!-- OLVIDE CONTRASEÑA -->

                <div class="opciones">

                    <a href="recuperar.php">
                        ¿Olvidaste tu contraseña?
                    </a>

                </div>


                <!-- BOTÓN -->

                <button type="submit">
                    INGRESAR
                </button>

            </form>


            <!-- SEPARADOR -->

            <div class="separador">

                <span></span>

                <p>o</p>

                <span></span>

            </div>


            <!-- REDES -->
            <!--<div class="redes">--

                <button class="social">
                    <i class="fa-brands fa-google"></i>
                    Google
                </button>

                <button class="social">
                    <i class="fa-brands fa-facebook"></i>
                    Facebook
                </button>

            </div>


            <!-- REGISTRO -->

            <p class="registro">

                ¿No tenés una cuenta?

               <a href="registro.php">Registrate</a>
                    Registrate
                </a>

            </p>

        </div>

    </div>

</body>

</html>