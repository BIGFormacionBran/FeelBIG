# Rutas y Ciclo de Vida

Feel BiG utiliza un sistema de **Rutas Amigables** gestionado por un controlador frontal.

## 1. El Front Controller (`index.php`)
Captura todas las peticiones y las procesa a través de `RouterManager.php`.

## 2. El Router Manager (`includes/managers/RouterManager.php`)
Es el cerebro del enrutamiento. Su ciclo de vida es:
1. **Detección de Assets**: Si la URL apunta a una imagen, CSS o JS, el router los sirve con el `Content-Type` correcto tras verificar permisos (especialmente en `/admin/`).
2. **Rutas Estáticas**: Busca archivos en `includes/pages/` (ej. `login`, `config`).
3. **Rutas Dinámicas**: Si la ruta no existe físicamente, consulta a `ContentDao::getCategoryBySlug`. Si encuentra una coincidencia, carga `CategoryView.php`.
4. **Manejo de Errores**: Si nada coincide, redirige a una página 404 personalizada.

## 3. Breadcrumbs Dinámicos
Ubicados en `MainManager::getBreadcrumbs`, generan la línea de navegación basada en la jerarquía de categorías (`id_padre`) definida en la base de datos, permitiendo una navegación intuitiva por niveles.