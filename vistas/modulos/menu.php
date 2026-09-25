<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>

<nav class="navbar navbar-expand-lg bg-light">
    <div class="container">
        <a class="navbar-brand" href="/cv/index.php">cvWeb</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/cv/index.php">Inicio</a>
                </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/cv/vistas/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/cv/vistas/register.php">Registro</a>
                    </li>
            </ul>
        </div>
    </div>
</nav>
