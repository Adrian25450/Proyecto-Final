// Login JavaScript
const API_URL = '/backend/api';

document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const messageDiv = document.getElementById('message');
    const submitBtn = e.target.querySelector('button[type="submit"]');
    
    // Limpiar mensaje anterior
    messageDiv.className = 'message';
    messageDiv.textContent = '';
    
    // Deshabilitar botón mientras se procesa
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="loading"></span> Iniciando sesión...';
    
    try {
        const response = await fetch(`${API_URL}/auth.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                username: username,
                password: password
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            messageDiv.className = 'message success';
            messageDiv.textContent = '¡Login exitoso! Redirigiendo...';
            
            // Guardar información del usuario
            localStorage.setItem('user', JSON.stringify(data.data));
            
            // Redirigir según el rol
            setTimeout(() => {
                if (data.data.rol === 'estudiante') {
                    window.location.href = 'estudiante.html';
                } else if (data.data.rol === 'profesor') {
                    window.location.href = 'profesor.html';
                }
            }, 1000);
        } else {
            messageDiv.className = 'message error';
            messageDiv.textContent = data.message || 'Error al iniciar sesión';
            submitBtn.disabled = false;
            submitBtn.textContent = 'Iniciar Sesión';
        }
    } catch (error) {
        console.error('Error:', error);
        messageDiv.className = 'message error';
        messageDiv.textContent = 'Error de conexión. Por favor, intente nuevamente.';
        submitBtn.disabled = false;
        submitBtn.textContent = 'Iniciar Sesión';
    }
});
