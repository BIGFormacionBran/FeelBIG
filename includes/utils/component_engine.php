<?php
/**
 * Escanea una carpeta y renderiza todos los componentes .php encontrados
 * @param string $folder Ruta de la carpeta
 */
function renderAutoComponents($folder) {
    $path = __DIR__ . '/../../' . $folder;
    
    if (!is_dir($path)) return;

    // Escaneamos archivos .php y los ordenamos alfabéticamente
    $components = glob($path . "/*.php");
    sort($components);

    foreach ($components as $component) {
        include $component;
    }
}