# Desarrollo de SGD WEB para un LOPDP en Ecuador

Este un trabajo realizado para el TFG (Trabajo de fin de grado) del grado de Informatica de la universidad UNIR.

## Instalación

Asegúrate de tener instalado Node.js y PHP en tu sistema antes de comenzar.

ADjunto un link el cual es de gran ayuda para su instalación:
https://www.solvetic.com/tutoriales/article/12465-instalar-node-js-en-visual-studio-code/

Para php lo puede realizar mediante XAMPP:
https://www.ionos.es/digitalguide/servidores/herramientas/instala-tu-servidor-local-xampp-en-unos-pocos-pasos/

### FrontEnd (React.js)

1. Navega a la carpeta `FrontEnd`:
cd FrontEnd
2. Instala las dependencias:
npm install
3. Inicia la aplicación de React:
npm start
4. La aplicación estará disponible en [http://localhost:3000](http://localhost:3000).
### BackEnd (PHP)
1. Navega a la carpeta `BackEnd`:
cd BackEnd
2. Configura tu servidor web para apuntar a esta carpeta.
3. Asegúrate de tener un servidor PostrgreSQL configurado y crea una base de datos.
4. Importa el archivo SQL proporcionado en la base de datos.
5. Actualiza las configuraciones de conexión a la base de datos en el archivo `BackEnd\sgd\lib\db\config\config.ini`.
6. Accede a la aplicación en tu navegador.
## Estructura del Proyecto
apiJson.php: Esta clase permite crear un servidor API JSON con diversas funciones para las clases futuras. Facilita la creación de la API y el manejo de las funciones relacionadas.
log.php: Esta clase se encarga de generar registros de logs para todas las clases del sistema. Ayuda en la organización y seguimiento de eventos importantes durante la ejecución del programa.
Una vez que se tienen estas dos clases, se establece una estructura organizativa en las carpetas del backend para organizar las clases, APIs y logs. La estructura de carpetas se mantiene consistente en todo el proyecto. Dentro de la carpeta "SGD", se encuentran tres subcarpetas:
app: En esta carpeta se almacenan todas las APIs creadas. Cada API tiene su propia carpeta, por ejemplo, "apiClient". Dentro de esta carpeta, se encuentra el archivo "apiClient.php", que contiene la lógica de la API, y un archivo "autoload.php" que incluye las rutas de las clases utilizadas en la API. Además, se encuentra una carpeta "config" que contiene un archivo "config.ini" con la configuración del log y la conexión a la base de datos.
lib: Esta carpeta se organiza según los esquemas de la base de datos. Contiene tres subcarpetas:
common: Corresponde al esquema principal de la base de datos. Aquí se encuentran subcarpetas con el nombre de las tablas/clases que se utilizarán, como "client", "department", "reference", "fileToServer", así como las dos clases mencionadas anteriormente.
security: Es el esquema de seguridad de la base de datos. Contiene subcarpetas como "option", "rolUserSec" y "UserSec".
system: Representa el esquema del sistema de la base de datos. Contiene subcarpetas como "action", "article", "file", "process" y "Project".
Además, hay una carpeta "Db" que contiene la clase de conexión a la base de datos.
Log: Esta carpeta sigue la misma estructura que la carpeta "app", pero en lugar de archivos PHP, contiene los registros de logs generados por cada API.


## Recursos Adicionales

Una buena fuente de para aprender react.js es el siguiente curso en caso de interesarles :
https://www.udemy.com/share/103dum3@tGPncUZS8sllbVdnkhz6FYFm_0TP1GmP6ojAGmZgSRXJ-aXoPNM1_2-55Rf24TJZ/

Y para php y postgresql:
https://www.udemy.com/share/1013jU3@cyUHINWtGXeEmNgNHy6oTa_rDFZ0ZxNPgYzAr6d-qqWm3fX3-rJ2iYVkXp1SXEB3/

## Licencia

Pueden ver que la licencia establecida en el archivo Licence
