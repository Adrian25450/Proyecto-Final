# Sistema de Gestión de Documentos - Proyecto Final Cloud Computing

Este proyecto es un sistema web completo para la gestión de documentos, diseñado como proyecto final para la materia de Cloud Computing. Permite a los estudiantes subir documentos y a los profesores verlos y descargarlos.

## ✨ Características Principales

- **Autenticación de Usuarios**: Sistema de login seguro basado en sesiones PHP.
- **Roles de Usuario**:
    - **Estudiante**: Puede subir documentos (PDF, DOCX, etc.).
    - **Profesor**: Puede ver y descargar todos los documentos subidos por los estudiantes.
- **Base de Datos MySQL**: Persistencia de datos robusta y escalable.
- **phpMyAdmin**: Interfaz gráfica para la gestión de la base de datos.
- **Contenerización con Docker**: Todo el entorno (aplicación, base de datos, phpMyAdmin) está contenerizado para un despliegue fácil y consistente.
- **CI/CD con Jenkins**: Pipeline automatizado para la integración y despliegue continuo.

## 🚀 Tecnologías Utilizadas

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 8.1
- **Base de Datos**: MySQL 8.0
- **Servidor Web**: Apache
- **Contenedores**: Docker, Docker Compose
- **CI/CD**: Jenkins

## 🔧 Instalación y Ejecución (Recomendado con Docker)

Sigue estos pasos para levantar todo el entorno de desarrollo de forma rápida y sencilla.

### Requisitos Previos

- [Docker Desktop](https://www.docker.com/products/docker-desktop) instalado y en ejecución.
- [Git](https://git-scm.com/downloads) instalado.
- Un editor de código como [Visual Studio Code](https://code.visualstudio.com/).

### Pasos

1. **Clonar el Repositorio**
   ```bash
   git clone https://github.com/Adrian25450/Proyecto-Final.git
   cd Proyecto-Final
   ```

2. **Construir y Levantar los Contenedores**
   Este comando construirá la imagen de la aplicación, iniciará los contenedores de la aplicación, la base de datos y phpMyAdmin en segundo plano.
   ```bash
   docker-compose up --build -d
   ```

3. **Acceder a la Aplicación**
   - **Aplicación Web**: Abre tu navegador y ve a [http://localhost:8080/frontend/](http://localhost:8080/frontend/)
   - **phpMyAdmin**: Para gestionar la base de datos, ve a [http://localhost:8081](http://localhost:8081)
     - **Servidor**: `mysql`
     - **Usuario**: `root`
     - **Contraseña**: `root`

4. **Detener los Contenedores**
   Para detener todos los servicios, ejecuta:
   ```bash
   docker-compose down
   ```

## 👥 Usuarios de Prueba

- **Estudiante**:
  - **Usuario**: `estudiante1`
  - **Contraseña**: `123456`
- **Profesor**:
  - **Usuario**: `profesor1`
  - **Contraseña**: `123456`

## 📂 Estructura del Proyecto

```
.
├── backend/                # Lógica del servidor en PHP
│   ├── api/                # Endpoints (auth, upload, etc.)
│   ├── config/             # Conexión a la BD
│   └── uploads/            # Carpeta para archivos subidos
├── database/               # Scripts de la base de datos
│   └── schema.sql          # Estructura e inserción de datos
├── frontend/               # Interfaz de usuario
│   ├── css/
│   ├── js/
│   └── *.html
├── Dockerfile              # Define el entorno de la aplicación
├── docker-compose.yml      # Orquesta los servicios (web, db, phpmyadmin)
├── Jenkinsfile             # Define el pipeline de CI/CD
└── README.md               # Este archivo
```

## ⚙️ Pipeline de CI/CD con Jenkins

El `Jenkinsfile` incluido en el repositorio define un pipeline que automatiza las siguientes tareas:
1. **Checkout**: Clona el código fuente desde GitHub.
2. **Build**: Construye la imagen de Docker de la aplicación.
3. **Login**: Se autentica en Docker Hub.
4. **Push**: Sube la imagen construida a Docker Hub.
5. **Deploy**: Detiene los contenedores antiguos y despliega la nueva versión usando `docker-compose`.
6. **Cleanup**: Limpia imágenes de Docker innecesarias para liberar espacio.

---
*Proyecto realizado por [Tu Nombre] para el curso de Cloud Computing.*
