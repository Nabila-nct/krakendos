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
        <h1 class="text-center my-4"><?php echo obtenerNombreCategoria(basename($_SERVER['PHP_SELF'])); ?>MagSafe</h1>
        
        <div class="row align-items-center mb-5">
            <div class="col-md-6 text-center">
                <img src="/assets/images/magsafe/magsafe.jpg" class="audifonos-uno" alt="Bocina MagSafe">
            </div>
            <div class="col-md-6">
                <h2>Bocina MagSafe</h2>
                <p>Potente bocina bluetooth con un imán MagSafe en su base para llevarla a todos lados.</p>
                <h3>$249</h3>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-md-6 order-md-2 text-center">
                <img src="/assets/images/magsafe/powerbank.jpg" class="audifonos-uno-dos" alt="Powerbank MagSafe">
            </div>
            <div class="col-md-6 order-md-1">
                <h2>Powerbank MagSafe</h2>
                <p>10,000 mAh, carga rapida, proteccion contra sobre carga, no daña la batería, carga tu iPhone, Apple Watch o AirPods por MagSafe, ademas cuenta con salida USB, USB C y Lightning.</p>
                <h3>$549</h3>
            </div>
        </div>
    </div>

    <div class="row align-items-center mb-5">
        <div class="col-md-6 text-center">
            <img src="/assets/images/magsafe/magsafe3.png" class="audifonos-uno" alt="Batería MagSafe">
        </div>
        <div class="col-md-6">
            <h2>Batería MagSafe</h2>
            <p>Batería con 5000mAh para carga inalámbrica MagSafe.</p>
            <h3>$349</h3>
        </div>
    </div>

    <div class="row align-items-center mb-5">
        <div class="col-md-6 order-md-2 text-center">
            <img src="/assets/images/magsafe/magsafe4.jpg" class="audifonos-uno-dos" alt="Tarjetero MagSafe">
        </div>
        <div class="col-md-6 order-md-1">
            <h2>Tarjetero MagSafe</h2>
            <p>Imán resistente, material de piel. Pregunta por los colores disponibles.</p>
            <h3>$299</h3>
        </div>
    </div>
</div>



<?php 
    // Mostrar productos dinámicamente desde la base de datos
    echo mostrarProductos($productos_actuales);
    ?>
    </div>


</body>
</html>