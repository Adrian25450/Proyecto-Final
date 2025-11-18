// Jenkinsfile para el pipeline de CI/CD del Sistema de Gestión de Documentos

pipeline {
    agent any

    environment {
        DOCKER_COMPOSE_VERSION = '1.29.2'
        DOCKER_HUB_CREDENTIALS = credentials('dockerhub-credentials')
        GITHUB_REPO = 'Adrian25450/Proyecto-Final'
    }

    stages {
        stage('1. Checkout') {
            steps {
                script {
                    echo "Clonando el repositorio de GitHub..."
                    checkout scm
                }
            }
        }

        stage('2. Build Docker Image') {
            steps {
                script {
                    echo "Construyendo la imagen de Docker..."
                    sh "docker build -t ${env.DOCKER_HUB_USER}/${GITHUB_REPO}:latest ."
                }
            }
        }

        stage('3. Login to Docker Hub') {
            steps {
                script {
                    echo "Iniciando sesión en Docker Hub..."
                    withCredentials([usernamePassword(credentialsId: 'dockerhub-credentials', usernameVariable: 'DOCKER_HUB_USER', passwordVariable: 'DOCKER_HUB_PASSWORD')]) {
                        sh "echo ${DOCKER_HUB_PASSWORD} | docker login -u ${DOCKER_HUB_USER} --password-stdin"
                    }
                }
            }
        }

        stage('4. Push Docker Image') {
            steps {
                script {
                    echo "Subiendo la imagen a Docker Hub..."
                    sh "docker push ${env.DOCKER_HUB_USER}/${GITHUB_REPO}:latest"
                }
            }
        }

        stage('5. Deploy Application') {
            steps {
                script {
                    echo "Desplegando la aplicación con Docker Compose..."
                    sh "docker-compose down"
                    sh "docker-compose up -d --build"
                }
            }
        }

        stage('6. Cleanup') {
            steps {
                script {
                    echo "Limpiando imágenes de Docker no utilizadas..."
                    sh "docker image prune -f"
                }
            }
        }
    }

    post {
        always {
            echo "Pipeline finalizado."
        }
        success {
            echo "¡El despliegue se ha completado exitosamente!"
        }
        failure {
            echo "¡El despliegue ha fallado!"
        }
    }
}
