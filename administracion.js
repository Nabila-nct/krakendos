 // Credenciales de administrador (En un caso real, esto estaría en el servidor)
 const adminUser = "admin";
 const adminPassword = "admin123";
 
 // Elementos del DOM
 const loginForm = document.getElementById('loginForm');
 const dashboard = document.getElementById('dashboard');
 const errorAlert = document.getElementById('errorAlert');
 const usernameInput = document.getElementById('username');
 const passwordInput = document.getElementById('password');
 const loginBtn = document.getElementById('loginBtn');
 const logoutBtn = document.getElementById('logoutBtn');
 
 // Verificar si ya hay una sesión iniciada
 window.onload = function() {
     const isLoggedIn = sessionStorage.getItem('adminLoggedIn');
     if (isLoggedIn === 'true') {
         showDashboard();
     }
 };
 
 // Función para validar el formulario
 loginBtn.addEventListener('click', function() {
     // Ocultar alerta de error si está visible
     errorAlert.style.display = 'none';
     
     // Obtener valores de los campos
     const username = usernameInput.value.trim();
     const password = passwordInput.value.trim();
     
     // Validar campos vacíos
     if (username === '' || password === '') {
         errorAlert.textContent = 'Por favor complete todos los campos';
         errorAlert.style.display = 'block';
         return;
     }
     
     // Validar credenciales
     if (username === adminUser && password === adminPassword) {
         // Guardar estado de la sesión
         sessionStorage.setItem('adminLoggedIn', 'true');
         showDashboard();
     } else {
         errorAlert.textContent = 'Usuario o contraseña incorrectos';
         errorAlert.style.display = 'block';
         passwordInput.value = '';
     }
 });
 
 // Función para mostrar el dashboard
 function showDashboard() {
     loginForm.style.display = 'none';
     dashboard.style.display = 'block';
 }
 
 // Cerrar sesión
 logoutBtn.addEventListener('click', function() {
     sessionStorage.removeItem('adminLoggedIn');
     dashboard.style.display = 'none';
     loginForm.style.display = 'block';
     usernameInput.value = '';
     passwordInput.value = '';
 });
 
 // Permitir enviar el formulario con la tecla Enter
 passwordInput.addEventListener('keypress', function(e) {
     if (e.key === 'Enter') {
         loginBtn.click();
     }
 });