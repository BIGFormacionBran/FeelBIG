# Arquitectura del Proyecto

El proyecto sigue una estructura organizada por capas para separar la lógica de negocio de la presentación, facilitando el mantenimiento y la escalabilidad.

### Carpetas Principales

* **/admin**: Ecosistema independiente para la gestión de contenidos. Posee sus propios activos y lógica de administración.
* **/assets**: Recursos estáticos públicos (CSS, JS, Imágenes).
* **/includes**: El núcleo de la aplicación.
    * `ajax/`: Endpoints para peticiones asíncronas.
    * `components/`: Fragmentos de UI reutilizables (Headers, Footers).
    * `daos/`: (**Data Access Objects**) Clases como `UserDao` y `ContentDao` que ejecutan SQL puro mediante PDO.
    * `managers/`: Clases como `UserManager` y `ContentManager` que procesan la lógica antes de entregar datos a la vista.
    * `pages/`: Los cuerpos de las vistas finales (ej. `Config.php`, `AuthView.php`).
    * `utils/`: Helpers globales (`DbUtil`, `LoggerUtil`, `MailUtil`).
* **/logs**: Almacenamiento de errores de sistema organizados por fecha.

### Flujo de Datos
La aplicación utiliza un patrón de delegación:
1. La **Página (View)** solicita una acción.
2. El **Manager** valida la lógica de negocio (ej. `UserManager::updateProfile`).
3. El **DAO** ejecuta la persistencia en la base de datos (ej. `UserDao::updateProfile`).