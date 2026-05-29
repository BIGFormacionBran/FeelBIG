# Modelado de Datos

Estructura de las principales entidades de la base de datos según los Data Access Objects (DAOs).

## Entidades de Usuario
- **USUARIO**: Almacena `nombre`, `correo`, `password` (hash) e `id_tipo_cuenta`.
- **REGISTRO_PENDIENTE**: Tabla temporal que almacena registros no verificados. Incluye `codigo` y `fecha` para control de expiración.

## Entidades de Contenido
- **CATEGORIA**: Estructura jerárquica mediante `id_padre`. Contiene `nombre`, `imagen` y `slug`.
- **CONTENIDO**: Artículos o videos vinculados a una categoría.
    - Campos: `nombre`, `descripcion_breve`, `imagen`, `video` (URL), `enlace_externo`, `fecha_publicacion` y `clasificacion` (badge).

## Relaciones
- Una **Categoría** puede tener muchas **Subcategorías**.
- Una **Categoría** tiene muchos **Contenidos**.
- Un **Usuario** tiene un **Tipo de Cuenta** que define su acceso al panel `/admin`.