pipeline {
    agent any

    environment {
        COMPOSER_HOME = "/usr/local/bin/composer" // Set Composer's home directory
    }

    tools {
        composer "composer" // Use the globally configured Composer installation from /usr/local/bin/composer
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm // Checkout the source code using the configured SCM (e.g., Git)
            }
        }

        stage('Install Dependencies') {
            steps {
                script {
                    sh "composer install" // Install PHP dependencies using Composer
                }
            }
        }

        // Add other stages as needed (e.g., Run Tests, Deploy)
    }

    post {
        always {
            cleanWs() // Clean up workspace after the build
        }
        success {
            echo 'Build and deployment successful!' // Notification on successful build
        }
        failure {
            echo 'Build or deployment failed.' // Notification on failed build
        }
    }
}
