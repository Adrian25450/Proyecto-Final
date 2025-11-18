// Profesor Dashboard JavaScript
const API_URL = '/backend/api';

document.addEventListener('DOMContentLoaded', () => {
    const user = JSON.parse(localStorage.getItem('user'));

    // Verificar si el usuario está logueado y es profesor
    if (!user || user.rol !== 'profesor') {
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

    // Cargar lista de documentos
    const documentsList = document.getElementById('documentsList');
    const documentsMessage = document.getElementById('documentsMessage');

    async function loadDocuments() {
        documentsList.innerHTML = '<tr><td colspan="6" style="text-align: center;">Cargando documentos...</td></tr>';

        try {
            const response = await fetch(`${API_URL}/documents.php`);
            const data = await response.json();

            if (data.success) {
                if (data.count > 0) {
                    documentsList.innerHTML = '';
                    data.data.forEach(doc => {
                        const row = `
                            <tr>
                                <td>${doc.titulo}</td>
                                <td>${doc.descripcion || 'N/A'}</td>
                                <td>${doc.usuario.nombre} (${doc.usuario.username})</td>
                                <td>${new Date(doc.fecha_subida).toLocaleString()}</td>
                                <td>${doc.nombre_archivo}</td>
                                <td>
                                    <a href="${API_URL}/download.php?id=${doc.id}" class="btn-download" target="_blank">Descargar</a>
                                </td>
                            </tr>
                        `;
                        documentsList.innerHTML += row;
                    });
                } else {
                    documentsList.innerHTML = '<tr><td colspan="6" class="no-documents">No hay documentos subidos por el momento.</td></tr>';
                }
            } else {
                documentsMessage.className = 'message error';
                documentsMessage.textContent = data.message || 'Error al cargar los documentos.';
                documentsMessage.style.display = 'block';
            }
        } catch (error) {
            console.error('Error:', error);
            documentsMessage.className = 'message error';
            documentsMessage.textContent = 'Error de conexión. Intente nuevamente.';
            documentsMessage.style.display = 'block';
        }
    }

    loadDocuments();
});
