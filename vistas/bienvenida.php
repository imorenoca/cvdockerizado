<?php require_once __DIR__."/modulos/header.php";
?>


<body>


    <div class="container mt-5">
        <!-- Primer apartado -->
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h1>Bienvenid@ a cvWeb</h1>
            </div>
        </div>

        <!-- Segundo apartado -->
        <div class="row mt-4">
            <div class="col-md-8 mx-auto text-center">
                <h3>Encuentra el trabajo perfecto para ti</h3>
            </div>
        </div>

        <!-- Tercer apartado -->
        <div class="row mt-4">
            <div class="col-md-8 mx-auto text-center">
                <p>cvWeb es la plataforma que te ayuda a gestionar las ofertas de empleo para que puedas encontrar el trabajo perfecto para ti. </p>
                <p>Podrás realizar el seguimiento de tus ofertas de trabajo enviadas en un sólo lugar.</p>
            </div>
        </div>
        <!-- Cuarto apartado -->
        <div class="row mt-4">
            <div class="col-md-6 offset-md-3 text-center">
                <!-- Botón para ir a la página de login -->
                <a href="vistas/login.php" class="btn btn-success btn-lg btn-block">Login</a>
                <!-- Botón para ir a la página de registro -->
                <a href="vistas/register.php" class="btn btn-primary btn-lg btn-block">Registro</a>
            </div>
        </div>
    </div>


    <br>
    <br><br><br><br><br><br>
    <?php require_once __DIR__."/modulos/footer.php"; ?>
</body>

</html>