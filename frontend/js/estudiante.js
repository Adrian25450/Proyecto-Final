// Estudiante Dashboard JavaScript
const API_URL = '/backend/api';

document.addEventListener('DOMContentLoaded', () => {
    const user = JSON.parse(localStorage.getItem('user'));
    
    // Verificar si el usuario está logueado y es estudiante
    if (!user || user.rol !== 'estudiante') {
        window.location.href = 'index.html';
        return;
    }
    
    // Mostrar información del usuario
    document.getElementById('userInfo').textContent = `Bienvenido, ${user.nombre}`;
    
    // Evento de logout
    document.getElementById('logoutBtn').addEventListener('click', () => {
        localStorage.removeItem('user');
        window.location.href = 'index.html';
    });
    
    // Evento de subir archivo
    const uploadForm = document.getElementById('uploadForm');
    const uploadMessage = document.getElementById('uploadMessage');
    
    uploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(uploadForm);
        const submitBtn = e.target.querySelector('button[type="submit"]');
        
        uploadMessage.className = 'message';
        uploadMessage.textContent = '';
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading"></span> Subiendo...';
        
        try {
            const response = await fetch(`${API_URL}/upload.php`, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                uploadMessage.className = 'message success';
                uploadMessage.textContent = '¡Documento subido exitosamente!';
                uploadForm.reset();
            } else {
                uploadMessage.className = 'message error';
                uploadMessage.textContent = data.message || 'Error al subir el documento.';
            }
        } catch (error) {
            console.error('Error:', error);
            uploadMessage.className = 'message error';
            uploadMessage.textContent = 'Error de conexión. Intente nuevamente.';
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Subir Documento';
        }
    });
});
