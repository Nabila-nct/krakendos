<!DOCTYPE html>
<?php
// Incluir el archivo de integración
require_once 'src/database/integracion.php';
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

    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Fuente -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&display=swap" rel="stylesheet">

    <!-- Script principal para cargar componentes -->
    <script src="/kraken-store/src/js/main.js"></script>
</head>
<body>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
          <a class="logo-navbar" href="/kraken-store/"><img src="/assets/images/logo/kraken-logo.jpeg" alt="logo kraken store" class="logo-kraken" height=80px></a>
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
                <?php echo generarMenuCategorias(); ?>
                  <li><a class="dropdown-item" href="/src/pages/productos/audifonos.php">Audífonos</a></li>
                  <li><a class="dropdown-item" href="/src/pages/productos/apple-watch.php">Apple Watches</a></li>
                  <li><a class="dropdown-item" href="/src/pages/productos/proyectores.php">Proyectores</a></li>
                  <li><a class="dropdown-item" href="/src/pages/productos/magsafe.php">MagSafe</a></li>
                  <li><a class="dropdown-item" href="/src/pages/productos/cargadores.php">Cargadores</a></li>
                  <li><a class="dropdown-item" href="/src/pages/productos/cargadores-qi2.php">Cargadores MagSafe 3 en 1 Certificacion Qi2</a></li>
                  <li><a class="dropdown-item" href="/src/pages/productos/accesorios.php">Accesorios y Cargadores</a></li>
                </ul>
              <li class="nav-item">
              <a class="nav-link" href="/src/pages/contacto/contacto.php">Contacto</a>
              </li>
              
              <li class="nav-item">
              <a class="nav-link" href="/src/database/panel_admin.php">Administración</a>
              </li>
              
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <!-- Principal -->
      <div class="hero"> 
        <h1>Kraken Store</h1>
        <center>
        <img src="assets/images/misc/KrakenImagen.jpg" alt="imagen productos kraken">
      </center>
      <br>
        <h2>Tu tienda de confianza para accesorios tecnológicos</h2>
      </div>
      
      <br>
      <!-- Sobre nosotros -->

      <div class="sobre-nosotros">
        <h4>En Kraken Store somos apasionados por la tecnología. Nuestro objetivo es ofrecerte una selección curada de productos innovadores como audífonos, smartwatches, proyectores y más. Nos enfocamos en la calidad, el diseño y la funcionalidad para que encuentres justo lo que necesitas en un solo lugar.
          Explora nuestro catálogo y descubre lo mejor en accesorios tecnológicos. </h4>
      </div>

      <br>

      <!-- Beneficios -->
      <div class="beneficios">
        <h3 id="benefits">¿Por qué elegirnos?</h3>
        <ul>
        <li><i class="bi bi-truck"></i> Envío rápido y seguro</li>
        <li><i class="bi bi-bag-check"></i> Garantía en todos los productos</li>
        <li><i class="bi bi-chat-dots"></i> Atención personalizada</li>
        <li><i class="bi bi-credit-card"></i> Múltiples métodos de pago</li>

        </ul>

      </div>
  
    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Kraken Store. Todos los derechos reservados.</p>
    </footer>
</body>
</html>