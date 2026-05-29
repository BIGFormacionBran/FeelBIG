# Feel BiG - Formación de Alto Impacto

Bienvenido al repositorio central de **Feel BiG**, una plataforma web desarrollada en PHP para la gestión y visualización de contenidos formativos, incluyendo un panel de administración avanzado y un sistema de autenticación seguro.

## 📁 Documentación del Proyecto

Para facilitar la navegación y el mantenimiento, la documentación se ha dividido en las siguientes secciones:

1.  [**Arquitectura y Estructura**](docs/arquitectura.md): Conoce cómo está organizado el proyecto y su jerarquía de carpetas.
2.  [**Sistema de Rutas y Bootstrapping**](docs/rutas.md): Explicación del funcionamiento de `.htaccess` y el motor de renderizado.
3.  [**Autenticación y Seguridad**](docs/autenticacion.md): Detalles sobre el flujo de registro, login y protección de rutas.
4.  [**Utilidades y Core**](docs/utilidades.md): Documentación de las clases Helper (Base de datos, Logs, Mail, Config).
5.  [**Componentes y UI**](docs/ui.md): Información sobre el sistema de renderizado de vistas, tarjetas y módulos dinámicos.

---

## 🚀 Tecnologías Utilizadas

* **Backend:** PHP 8.x (Arquitectura MVC simplificada con Managers/DAOs).
* **Servidor:** Apache con `mod_rewrite`.
* **Base de Datos:** MySQL (PDO).
* **Frontend:** HTML5, CSS3 (Minificación automática), JavaScript Vanilla.
* **Librerías:** PHPMailer, Swiper JS.

## 🛠️ Instalación Rápida

1.  Clona el repositorio.
2.  Configura el archivo `.env` en la raíz (usa como base los parámetros de `DbUtil` y `MailUtil`).
3.  Asegúrate de que la carpeta `logs/` tenga permisos de escritura.
4.  Apunta tu servidor web a la raíz del proyecto.