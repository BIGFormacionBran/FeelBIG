<?php
function getMinifiedCssUtil($filename = 'styles', $dir = 'assets/css') {
    // Aseguramos que el path termine en /
    $dir = rtrim($dir, '/') . '/';
    $source = $dir . "{$filename}.css";
    $target = $dir . "{$filename}.min.css";

    if (!file_exists($source)) return '';

    // Si el minificado no existe o el original es más reciente, regeneramos
    if (!file_exists($target) || filemtime($source) > filemtime($target)) {
        $content = file_get_contents($source);
        // Minificación básica: quitar comentarios y espacios
        $minified = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
        $minified = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    '], '', $minified);
        file_put_contents($target, $minified);
    }
    
    // Devolvemos la ruta con un parámetro de versión para evitar caché
    return $target . '?v=' . filemtime($target);
}