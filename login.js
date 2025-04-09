// Script para el panel de administrador
document.addEventListener('DOMContentLoaded', function() {
    var adminUser = "admin";
    var adminPassword = "admin123";
    
    // Referencias a elementos DOM
    var loginForm = document.getElementById('loginForm');
    var adminPanel = document.getElementById('adminPanel');
    var errorAlert = document.getElementById('errorAlert');
    var usernameInput = document.getElementById('username');
    var passwordInput = document.getElementById('password');
    var loginBtn = document.getElementById('loginBtn');
    var logoutBtn = document.getElementById('logoutBtn');
    
    // Verificar si ya hay sesión iniciada
    if (sessionStorage.getItem('adminLoggedIn') === 'true') {
        showAdminPanel();
    }
    
    // Evento de clic en el botón de login
    loginBtn.addEventListener('click', function() {
        var username = usernameInput.value.trim();
        var password = passwordInput.value.trim();
        
        // Ocultar mensaje de error anterior
        errorAlert.style.display = 'none';
        
        // Validar campos vacíos
        if (username === '' || password === '') {
            errorAlert.textContent = 'Por favor complete todos los campos';
            errorAlert.style.display = 'block';
            return;
        }
        
        // Validar credenciales
        if (username === adminUser && password === adminPassword) {
            // Guardar sesión
            sessionStorage.setItem('adminLoggedIn', 'true');
            showAdminPanel();
        } else {
            errorAlert.textContent = 'Usuario o contraseña incorrectos';
            errorAlert.style.display = 'block';
            passwordInput.value = '';
        }
    });
    
    // Función para mostrar el panel de administración
    function showAdminPanel() {
        document.body.classList.remove('login-page');
        document.body.classList.add('admin-page');
        loginForm.style.display = 'none';
        adminPanel.style.display = 'block';
    }
    
    // Cerrar sesión
    logoutBtn.addEventListener('click', function() {
        sessionStorage.removeItem('adminLoggedIn');
        adminPanel.style.display = 'none';
        loginForm.style.display = 'block';
        document.body.classList.remove('admin-page');
        document.body.classList.add('login-page');
        usernameInput.value = '';
        passwordInput.value = '';
    });
    
    // Permitir enviar con Enter
    passwordInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            loginBtn.click();
        }
    });
});

// Mensaje para verificar carga
console.log("Script cargado correctamente");