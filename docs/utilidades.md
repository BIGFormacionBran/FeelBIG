# Utilidades del Core (Utils)

Clases estáticas que proporcionan funcionalidad global:

### `DbUtil.php`
Implementa el patrón **Singleton** para la conexión PDO. Incluye reintentos y configuración de charset UTF-8.

### `ConfigUtil.php`
Cargador de variables de entorno desde el archivo `.env`. Evita la exposición de credenciales en el código.

### `MailUtil.php`
Wrapper sobre **PHPMailer**.
* Configura automáticamente el servidor SMTP.
* Genera correos electrónicos con un diseño corporativo (Header con logo y Footer de firma) inyectando el cuerpo dinámicamente.

### `LoggerUtil.php`
Sistema de log personalizado que organiza los mensajes por niveles (`INFO`, `ERROR`) y crea archivos diarios automáticamente en la carpeta `/logs`.

### `AssetsUtil.php`
Incluye un **Minificador de CSS** en tiempo real. Si el archivo `.css` original es más reciente que el `.min.css`, lo recompila automáticamente eliminando comentarios y espacios.