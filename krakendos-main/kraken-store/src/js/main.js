/**
 * Script principal para el sitio Kraken Store
 * Maneja la carga de componentes, navegación y funcionalidad común
 */

// Ejecutar cuando el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    console.log('Kraken Store - Sitio web cargado');
    
    // Marcar el enlace de navegación activo
    highlightActiveNavLink();
    
    // Inicializar tooltips de Bootstrap si existen
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Cargar componentes reutilizables si los hay
    loadComponents();
});

/**
 * Resalta el enlace de navegación activo según la URL actual
 */
function highlightActiveNavLink() {
    const currentPath = window.location.pathname;
    
    // Obtener todos los enlaces de navegación
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    
    // Quitar la clase active de todos los enlaces
    navLinks.forEach(link => {
        link.classList.remove('active');
    });
    
    // Añadir la clase active al enlace correspondiente a la página actual
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        
        // Si es la página de inicio y estamos en la raíz del proyecto
        if (href === '/kraken-store/' && (currentPath === '/kraken-store/' || currentPath === '/kraken-store/index.html')) {
            link.classList.add('active');
        } 
        // Para los demás enlaces, comprobar si la URL actual termina con su href
        else if (href !== '/kraken-store/' && currentPath.endsWith(href)) {
            link.classList.add('active');
        }
        // Si es un enlace de categoría de productos y estamos en una página de producto
        else if (link.classList.contains('dropdown-toggle') && currentPath.includes('/productos/')) {
            link.classList.add('active');
        }
    });
}

/**
 * Carga componentes HTML reutilizables desde la carpeta components
 */
function loadComponents() {
    // Buscar elementos que deben cargar componentes
    const componentElements = document.querySelectorAll('[data-component]');
    
    if (componentElements.length === 0) return;
    
    // Para cada elemento, cargar el componente correspondiente
    componentElements.forEach(element => {
        const componentName = element.getAttribute('data-component');
        
        fetch(`/kraken-store/src/components/${componentName}.html`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error cargando el componente ${componentName}: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                // Si el componente es el header, reemplazar el título si existe el marcador
                if (componentName === 'header' && html.includes('%%TITLE%%')) {
                    const pageTitle = document.title.replace('Kraken Store - ', '');
                    html = html.replace('%%TITLE%%', pageTitle || 'Tienda de Tecnología');
                }
                
                // Insertar el HTML del componente
                element.innerHTML = html;
                
                // Si el componente es navbar, resaltar el enlace activo
                if (componentName === 'navbar') {
                    highlightActiveNavLink();
                }
                
                // Disparar evento para notificar que el componente se ha cargado
                const event = new CustomEvent('componentLoaded', { detail: { name: componentName } });
                document.dispatchEvent(event);
            })
            .catch(error => {
                console.error('Error al cargar componente:', error);
                element.innerHTML = `<div class="alert alert-danger">Error al cargar el componente "${componentName}"</div>`;
            });
    });
}

/**
 * Añade una clase de animación a los productos cuando se hace scroll
 * Para mejorar la experiencia de usuario
 */
window.addEventListener('scroll', function() {
    const productRows = document.querySelectorAll('.row');
    
    productRows.forEach(row => {
        // Comprobar si el elemento está en el viewport
        const rect = row.getBoundingClientRect();
        const isInViewport = rect.top <= window.innerHeight && rect.bottom >= 0;
        
        if (isInViewport) {
            // Si no tiene ya la clase, añadirla
            if (!row.classList.contains('visible')) {
                row.classList.add('visible');
            }
        }
    });
});