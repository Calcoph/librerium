# Librerium
Página web para publicar libros, reseñarlos y comentarlos.

Hecho por: Francisco González, Diego Esteban, Ibai Mendivil.

## Instrucciones
En caso de que alguno de los siguientes comandos de un error de permisos, hay que ejecutarlo poniendo `sudo` delante.

1. Crear la imagen **web**:
    * `$ docker build -t="web" .`
2. Iniciar los contenedores:
    * `$ docker-compose up -d`
3. Asegurarse de que el contenedor **librerium_web_1** existe:
    * `$ docker ps -a`
4. En caso de no existir, cambiar el comando del paso 5 para que sea el contenedor que tenga **web** en alguna parte de su nombre, en vez de `librerium_web_1`
5. Dar permiso a php para escribir en **/home/www-data/uploads** (Esto es porque para poder subir una portada personalizada para el libro, porque php utiliza el usuario www-data, pero docker-compose monta el volumen siempre como root):
    * `$ docker exec librerium_web_1 chown www-data:www-data /var/www/html/uploads`
6. Abrir el navegador en **http://localhost:8890/**
8. Iniciar sesión con usuario: **admin**, contraseña: **test**
7. Importar la base de datos **database.sql**:
    1. Seleccionar la base de datos **database** ![](resources/elegir_database.png)
    2. Cambiar a la pestaña **import** ![](resources/import.png)
    3. Pinchar en **browse** y elegir **database.sql** ![](resources/browse.png)
    4. Scrollear al final de la página y pinchar en **import** ![](resources/import_btn.png)
8. Abrir el navegador en **http://localhost:81/**
