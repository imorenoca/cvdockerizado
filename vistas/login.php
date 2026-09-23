<?php include_once (__DIR__ . '/modulos/header.php'); ?>
<?php require_once (__DIR__.'/modulos/menu.php'); ?>

<main class="form-signin" style="max-width: 400px; margin: 0 auto; padding: 15px;">
    <form method="POST" action="">

        <h1 class="h3 mt-5 fw-normal text-center">Login</h1>
        <div class="form-floating">
            <input name="usuario" type="text" class="form-control mb-3" id="floatingInput" placeholder="user.name">
            <label for="floatingInput">Usuario</label>
        </div>

        <div class="form-floating">
            <input name="password" type="password" class="form-control mb-3" id="floatingPassword"
                placeholder="Password">
            <label for="floatingPassword">Contraseña</label>
        </div>
        <!--<div class="text-center">
            <h6 class="font-italic mt-3"> <a href=""> Olvidé mi Contraseña </a> </h6>
        </div>-->
        <input name="btningresar" class="w-100 btn btn-lg btn-success" type="submit" value="Iniciar Sesión">

        <div class="text-center">

            <h6 class="mt-3">¿No tienes cuenta? <a href="/register">Crea una cuenta</a></h6>
        </div>
    </form>
</main>

<?php require_once __DIR__."/modulos/footer.php"; ?>
