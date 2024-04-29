# Documentación para Ejecutar Localmente un Proyecto de Laravel 11

## Requisitos Previos

Antes de ejecutar el proyecto localmente, asegúrate de tener instalado lo siguiente en tu sistema:

-   [PHP](https://www.php.net/) (versión >= 8.2)
-   [Composer](https://getcomposer.org/)
-   [Git](https://git-scm.com/)

## Pasos para Ejecutar el Proyecto

1. **Clonar el Repositorio:**

    Clona el repositorio del proyecto desde GitHub:

    ```bash
    git clone git@github.com:chaicopadillag/learn-api-laravel.git
    ```

2. **Instalar Dependencias de PHP:**

    Accede al directorio del proyecto y ejecuta el siguiente comando para instalar las dependencias de PHP:

    ```bash
    composer install
    ```

3. **Configuración del Entorno:**

    Copia el archivo `.env.example` y renómbralo como `.env`. Luego, configura las variables de entorno según tu configuración local, como la conexión a la base de datos, etc.

    ```bash
    cp .env.example .env
    ```

4. **Generar Clave de la Aplicación:**

    Genera una nueva clave de aplicación ejecutando el siguiente comando:

    ```bash
    php artisan key:generate
    ```

    Importante setear el valor de `ADMIN_PASSWORD` en env para enviar semillas

5. **Ejecutar las Migraciones y Semillas:**

    Ejecuta las migraciones de la base de datos y las semillas para poblar la base de datos:

    ```bash
    php artisan migrate
    ```

    ```bash
    php artisan db:seed
    ```

6. **Iniciar el Servidor Local:**

    Por último, inicia el servidor local ejecutando:

    ```bash
    php artisan serve
    ```

    Esto iniciará el servidor en `http://localhost:8000`. Puedes acceder a tu aplicación desde tu navegador web.

## Autor

Api desarrollador por [Gerardo Chaico](https://chaicopadillag.github.io/).
