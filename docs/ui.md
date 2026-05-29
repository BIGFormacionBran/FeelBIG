# Interfaz y Renderizado

## Componentes Dinámicos
En la Home, se utiliza `renderAutoComponentsUtil('includes/components/home_modules')`. Esta función escanea la carpeta de módulos, los ordena alfabéticamente y los incluye automáticamente, permitiendo añadir nuevas secciones a la landing page simplemente creando archivos `.php` numerados (01.php, 02.php, etc.).

## Sistema de Tarjetas (`CardRenderUtil.php`)
Proporciona dos formas de visualizar contenido:
1.  **Columnas (`renderCardItemColumn`)**: Ideal para grids.
2.  **Filas (`renderCardItemRow`)**: Ideal para listas o buscadores.

## Vista Individual (`IndividualRenderUtil.php`)
Este motor es capaz de transformar una URL amigable (slug) en una consulta a la base de datos para mostrar un artículo o contenido específico.
* Soporta detección automática de vídeos de **YouTube** (convirtiendo links normales a embeds).
* Gestiona layouts duales: si hay imagen y vídeo, aplica un grid especial; si no, centra el contenido.