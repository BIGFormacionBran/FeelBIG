# Autenticación y Registro

El sistema implementa un flujo de seguridad de doble paso y gestión de perfiles.

## Flujo de Registro (`UserManager.php`)
1. **Inicio**: El usuario envía datos a `startRegistration`.
2. **Temporal**: Se genera un código de 6 dígitos y se almacena un hash de la contraseña en la tabla `REGISTRO_PENDIENTE` vía `PendingRegistrationDao`.
3. **Notificación**: `MailManager` envía el código al usuario.
4. **Confirmación**: Al validar el código, los datos se mueven a la tabla definitiva `USUARIO` y se elimina el registro temporal.

## Gestión de Sesión y Perfil
* **Login**: `UserDao::login` utiliza `password_verify` para validar las credenciales contra el hash BCRYPT.
* **Actualización de Perfil**: En `Config.php`, el sistema permite actualizar nombre, correo y contraseña de forma independiente. Si la contraseña se deja en blanco, se mantiene la actual.

## Seguridad
* **Hashing**: Se utiliza `PASSWORD_BCRYPT` para todas las claves.
* **Roles**: El sistema distingue entre usuarios y administradores (`isAdmin` verifica roles 1 o 2).
* **Limpieza**: El sistema elimina automáticamente registros pendientes con más de 1 hora de antigüedad durante cada nuevo intento de registro.