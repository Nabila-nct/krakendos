<!DOCTYPE html>
<?php
// Incluir el archivo de integración al principio
require_once '/Users/nabilagunesvela/Desktop/krakendos-main/kraken-store/src/database/integracion.php';
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kraken Store</title>
    <link rel="stylesheet" href="/src/css/style.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</head>

<body>

<!-- navbar -->
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
      <a class="logo-navbar" href="/kraken-store/"><img src="/assets/images/logo/kraken-logo.jpeg" alt="logo kraken store" class="logo-kraken"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/kraken-store/">Inicio</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Productos
            </a>
            <ul class="dropdown-menu">
            <li><a class="dropdown-item active" href="/src/pages/productos/audifonos.php">Audífonos</a></li>
            <li><a class="dropdown-item" href="/src/pages/productos/apple-watch.php">Apple Watches</a></li>
            <li><a class="dropdown-item" href="/src/pages/productos/proyectores.php">Proyectores</a></li>
            <li><a class="dropdown-item" href="/src/pages/productos/magsafe.php">MagSafe</a></li>
            <li><a class="dropdown-item" href="/src/pages/productos/cargadores.php">Cargadores</a></li>
            <li><a class="dropdown-item" href="/src/pages/productos/cargadores-qi2.php">Cargadores MagSafe 3 en 1 Certificacion Qi2</a></li>
            <li><a class="dropdown-item" href="/src/pages/productos/accesorios.php">Accesorios y Cargadores</a></li>
            </ul>
          <li class="nav-item">
          <a class="nav-link" href="/kraken-store/src/pages/contacto/contacto.php">Contacto</a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="/kraken-store/src/pages/admin/login.html">Administración</a>
          </li>
          
          </li>
        </ul>
      </div>
    </div>
  </nav>

    <div class="container">
        <h1 class="text-center my-4"><?php echo obtenerNombreCategoria(basename($_SERVER['PHP_SELF'])); ?>Cargadores MagSafe 3 en 1 Certificacion Qi2</h1>
        
        <div class="row align-items-center mb-5">
            <div class="col-md-6 text-center">
                <img src="/assets/images/misc/q12rotativo.jpg" class="audifonos-uno" alt="AirPods Primera Generación">
            </div>
            <div class="col-md-6">
                <h2>Cargador Qi2 Rotativo</h2>
                <p>Cargador 3 en 1 con sistema giratorio original con certificado Qi2, certificado de protección a la batería de tu iphone y carga rápida. Carga Airpods, Apple Watch y iphone a la vez.</p>
                <h3>$1,649</h3>
                <h4>*Original</h4>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-md-6 order-md-2 text-center">
                <img src="/assets/images/misc/viaje.png" class="audifonos-uno-dos" alt="AirPods Segunda Generación">
            </div>
            <div class="col-md-6 order-md-1">
                <h2>Cargador Qi2 Viaje </h2>
                <p>Lo mismo que el anterior, 3 en 1 pero extremadamente compacto, es plegable, ideal para tus viajes, cabrá en tu bolsillo.</p>
                <h5> NO DAÑAN LA BATERÍA DE TU IPHONE</h5>                
                <h3>$1,099</h3>
                <h4>*Original</h4>
            </div>
        </div>
    </div>

    <div class="row align-items-center mb-5">
        <div class="col-md-6 text-center">
            <img id="airpods3gen" src="/assets/images/misc/compacto.png" class="audifonos-uno" alt="AirPods Tercera Generación">
        </div>
        <div class="col-md-6">
            <h2>Cargador Qi2 compacto</h2>
            <p>La versión más elegante para escritorio, con una luz cálida en su base, cargará tu Airpods, Apple Watch y iphone.</p>
            <h3>$1,099</h3>
            <p>*Original</p>
        </div>
    </div>

    <div class="row align-items-center mb-5">
        <div class="col-md-6 order-md-2 text-center">
            <img src="/assets/images/cargadores/cargadorescritorio.jpg" class="audifonos-uno-dos" alt="AirPods Segunda Generación">
        </div>
        <div class="col-md-6 order-md-1">
            <h2>Cargador de escritorio</h2>
            <p>Un cargador más accesible pero manteniéndose elegante 3 en 1 con cuerpo de aluminio.</p>
            <h3>$849</h3>
        </div>
    </div>

    <?php 
    // Mostrar productos dinámicamente desde la base de datos
    echo mostrarProductos($productos_actuales);
    ?>
</div>


        
           
</body>

</html>