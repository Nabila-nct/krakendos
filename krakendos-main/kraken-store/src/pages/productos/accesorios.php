<?php
// Incluir el archivo de integración al principio
require_once '/Users/nabilagunesvela/Desktop/krakendos-main/kraken-store/src/database/integracion.php';
?>

<!DOCTYPE html>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kraken Store</title>
    <link rel="stylesheet" href="/kraken-store/src/css/style.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</head>

<body>

   <!-- navbar -->
<nav class="navbar navbar-expand-lg bg-light">
  <div class="container-fluid">
  <a class="logo-navbar" href="/"><img src="/kraken-store/assets/images/logo/kraken-logo.jpeg" alt="logo kraken store" class="logo-kraken"></a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/">Inicio</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Productos
          </a>
          <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="/kraken-store/src/pages/productos/audifonos.php">Audífonos</a></li>
            <li><a class="dropdown-item" href="/kraken-store/src/pages/productos/apple-watch.php">Apple Watches</a></li>
            <li><a class="dropdown-item" href="/kraken-store/src/pages/productos/proyectores.php">Proyectores</a></li>
            <li><a class="dropdown-item" href="/kraken-store/src/pages/productos/magsafe.php">MagSafe</a></li>
            <li><a class="dropdown-item" href="/kraken-store/src/pages/productos/cargadores.php">Cargadores</a></li>
            <li><a class="dropdown-item" href="/kraken-store/src/pages/productos/cargadores-qi2.php">Cargadores MagSafe 3 en 1 Certificacion Qi2</a></li>
            <li><a class="dropdown-item" href="/kraken-store/src/pages/productos/accesorios.php">Accesorios y Cargadores</a></li>
          </ul>
        <li class="nav-item">
          <a class="nav-link" href="/kraken-store/src/pages/contacto/contacto.php">Contacto</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="/kraken-store/src/database/panel_admin.php">Administración</a>
        </li>
        
        </li>
      </ul>
    </div>
  </div>
</nav>

    <div class="container">
        <h1 class="text-center my-4"><?php echo obtenerNombreCategoria(basename($_SERVER['PHP_SELF'])); ?>Accesorios y cargadores</h1>
        
        <div class="row align-items-center mb-5">
            <div class="col-md-6 text-center">
                <img src="/assets/images/misc/androidtv.png" class="applewatch-mini" alt="Apple Watch Series 10 OEM" width="400px" height=auto>
            </div>
            <div class="col-md-6">
                <h2>Android TV</h2>
                <p>Con 4gb de RAM y 16gb de memoria, disfruta de todas tus plataformas, convierte cualquier TV en SmartIV</p>
                <p>41 mm de pantalla</p>
                <h3>$1199</h3>
                <p>*Calidad original</p>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-md-6 order-md-2 text-center">
                <img src="/assets/images/misc/applepencil.png" class="applewatch-proplus" alt="Apple Watch Series 10 OEM" width="400px" height=auto>
            </div>
            <div class="col-md-6 order-md-1">
                <h2>Apple Pencil</h2>
                <p>Para iPad, iPad pro, iPad air, ideal para diseño y apuntes</p>
                <h3>$249</h3>
            </div>
        </div>
    </div>

    <div class="row align-items-center mb-5">
        <div class="col-md-6 text-center">
            <img src="/assets/images/cargadores/cargadormgs.jpeg" class="applewatch-ultra" alt="Apple Watch Ultra 2 OEM" width="400px" height=auto>
        </div>
        <div class="col-md-6">
            <h2>Cargador Magsafe</h2>
                <p>Carga de iPhone, iPad o Apple watch de manera inalambrica</p>
                <h3>$399</h3>
        </div>
    </div>

    <div class="row align-items-center mb-5">
        <div class="col-md-6 order-md-2 text-center">
            <img src="/assets/images/cargadores/c-lightning.jpeg" class="applewatch-nike" alt="Apple Watch Serie 9 Nike Edition 1:1" width="400px" height=auto>
        </div>
        <div class="col-md-6 order-md-1">
            <h2>Cargador C-L</h2>
            <p>Cable C a lightning + Cubo 25w con engomado original</p>
            <h3>$249</h3>
        </div>
    </div>

    <?php 
    // Mostrar productos dinámicamente desde la base de datos
    echo mostrarProductos($productos_actuales);
    ?>
</div>

</body>

</html>