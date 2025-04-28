<?php
// Incluir el archivo de integración al principio
require_once '/Users/nabilagunesvela/Desktop/krakendos-main/kraken-store/src/database/integracion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kraken Store - Audífonos</title>
    <link rel="stylesheet" href="/src/css/style.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                <a class="nav-link" aria-current="page" href="/kraken-store/">Inicio</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                <a class="nav-link" href="/kraken-store/src/database/login.php">Administración</a>
              </li>
              
              </li>
            </ul>
          </div>
        </div>
      </nav>

    <div class="container">
        <h1 class="text-center my-4"><?php echo obtenerNombreCategoria(basename($_SERVER['PHP_SELF'])); ?></h1>
        
        <div class="row align-items-center mb-5">
            <div class="col-md-6 text-center">
                <img src="/assets/images/audifonos/airpods1gen.jpg" class="audifonos-uno" alt="AirPods Primera Generación">
            </div>
            <div class="col-md-6">
                <h2>AirPods Pro 1G OEM</h2>
                <p>Cancelación de ruido activa, modo ambiente y configuraciones del sistema iOS, número de serie válido, carga MagSafe. También compatible con Android.</p>
                <h3>$999</h3>
                <p>*Calidad original</p>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-md-6 order-md-2 text-center">
                <img src="/assets/images/audifonos/airpods2gen.jpeg" class="audifonos-uno-dos" alt="AirPods Segunda Generación">
            </div>
            <div class="col-md-6 order-md-1">
                <h2>AirPods Pro 2G OEM</h2>
                <p>Entrada C, MagSafe, cancelación de ruido activa, modo ambiente, número de serie válido. También compatible con Android.</p>
                <h4>Sin cancelación</h4>
                <h4>Calidad 1:1</h4>
                <h3>$549</h3>
                <h4>Con cancelación</h4>
                <h3>$1,299</h3>
                <p>*Calidad original</p>
            </div>
        </div>
        
        <div class="row align-items-center mb-5">
            <div class="col-md-6 text-center">
                <img id="airpods3gen" src="/assets/images/audifonos/airpods3gen.jpg" class="audifonos-uno" alt="AirPods Tercera Generación">
            </div>
            <div class="col-md-6">
                <h2>AirPods Pro 3G OEM</h2>
                <p>Configuraciones del sistema iOS, número de serie válido, controles de toque, excelente sonido y conectividad. Compatible con Android.</p>
                <h3>$899</h3>
                <p>*Calidad original</p>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-md-6 order-md-2 text-center">
                <img src="/assets/images/audifonos/airpods4gen.jpg" class="audifonos-uno-dos" alt="AirPods Segunda Generación">
            </div>
            <div class="col-md-6 order-md-1">
                <h2>AirPods 4G ANC OEM</h2>
                <p>Cancelación de ruido activa, modo ambiente, audio espacial, configuraciones del sistema iOS, número de serie válido, controles de toque. Compatible con Android.</p>
                <h3>$1,199</h3>
                <p>*Calidad original</p>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-md-6 text-center">
                <img src="/assets/images/audifonos/earpods.jpeg" class="audifonos-uno-dos" alt="EarPods OEM">
            </div>
            <div class="col-md-6">
                <h2>EarPods OEM</h2>
                <p>Excelente calidad de sonido, estuche protector, controles de volumen, conectividad y configuraciones de iOS. Compatible con Android.</p>            
                <h4>Jack</h4>
                <h3>$129</h3>
                <h4>Lightning</h4>
                <h3>$159</h3>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-md-6 order-md-2 text-center">
                <img src="/assets/images/audifonos/airpodsmax.webp" class="audifonos-uno-dos" alt="AirPods Max">
            </div>
            <div class="col-md-6 order-md-1">
                <h2>AirPods Max</h2>
                <p>Animación, smart case, almohadillas imantadas, cancelación de ruido activa, modo ambiente, cascos de aluminio, idéntico a original, no hay diferencia alguna, garantizado.</p>
                <h4>Calidad Clon</h4>
                <h3>$599</h3>
                <h4>Calidad Original</h4>
                <h3>$3,699</h3>
                <p>*Envío gratis</p>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-md-6 text-center">
                <img src="/assets/images/audifonos/haylou.jpg" class="audifonos-uno-dos" alt="Haylou s30">
            </div>
            <div class="col-md-6">
                <h2>Haylou s30</h2>
                <p>Originales, Cancelación de ruido activa, modo ambiente, micrófono gamer, larga duración de la batería.</p>
                <h3>$899</h3>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-md-6 order-md-2 text-center">
                <img id="airpods2gen" src="/assets/images/audifonos/airpods2gen.png" class="audifonos-uno-dos" alt="AirPods 2G OEM">
            </div>
            <div class="col-md-6 order-md-1">
                <h2>AirPods 2G OEM</h2>
                <p>Calidad de sonido premium, estuche de carga inalámbrica, controles táctiles, compatibilidad con iOS y Android.</p>
                <h3>$1,099</h3>
            </div>
        </div>

        <?php 
        // Mostrar productos dinámicamente desde la base de datos
        echo mostrarProductos($productos_actuales);
        ?>
    </div>
    
    <footer class="bg-light text-center text-lg-start mt-5">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
            © 2025 Kraken Store. Todos los derechos reservados.
        </div>
    </footer>
</body>
</html>