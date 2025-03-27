# Prueba tecnica para OpcionYO

Sigue estos pasos para levantar el proyecto correctamente:

**Tomando un enfoque como API en lugar de una app web utilizando blade debido que no especifica en el test como debe ser realizado**

1. **Clonar el repositorio**  
    Asegúrate de clonar el repositorio en tu máquina local:
    ```bash
    git clone https://github.com/DriluzRed/opcion_yo_test.git
    cd opcionyo_test
    ```

2. **Instalar dependencias**  
    Ejecuta el siguiente comando para instalar las dependencias necesarias:
    ```bash
    composer install
    ```

3. **Configurar variables de entorno**  
    Crea un archivo `.env` en la raíz del proyecto y configura las variables necesarias. Puedes usar el archivo `.env.example` como referencia:
    ```bash
    cp .env.example .env
    ```

4. **Iniciar el servidor de desarrollo** 

    Ejecutar las migraciones y seeders
    ```bash
    php artisan migrate
    ```
    ```bash
    php artisan db:seed
    ```

    Levanta el servidor local con:
    ```bash
    php artisan serve
    ```

5. **Acceder a la aplicación**  
    Puedes utilizar postman para realizar las pruebas.

    ```
    Verificar empleados disponibles:
    http://127.0.0.1:8000/api/available-employees?date=2025-04-10&time=12:00:00

    Verificar disponibilidad de horarios

    http://127.0.0.1:8000/api/check-availability?start_time=2025-04-04 13:00:00&end_time=2025-04-04 14:00:00

    Descargar excel 
    http://127.0.0.1:8000/export-excel

    ```
