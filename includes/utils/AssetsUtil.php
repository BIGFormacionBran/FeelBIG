<?php
require_once __DIR__ . '/LoggerUtil.php';

function getMinifiedCssUtil($filename = 'styles', $dir = 'assets/css') {
    // Aseguramos que el path termine en /
    $dir = rtrim($dir, '/') . '/';
    $source = $dir . "{$filename}.css";
    $target = $dir . "{$filename}.min.css";

    LoggerUtil::info("Procesando CSS: $filename en directorio: $dir");
    LoggerUtil::info("Ruta SOURCE absoluta: " . realpath($source));

    if (!file_exists($source)){
        LoggerUtil::error("SOURCE no existe: $source (Directorio actual: " . getcwd() . ")");
        return '';
    } 

    // Si el minificado no existe o el original es más reciente, regeneramos
    if (!file_exists($target) || filemtime($source) > filemtime($target)) {
        LoggerUtil::info("Regenerando minificado para: $filename");
        $content = file_get_contents($source);
        if ($content === false) {
            LoggerUtil::error("No se pudo leer el contenido de: $source");
            return '';
        }

        // Minificación básica: quitar comentarios y espacios
        $minified = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
        $minified = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    '], '', $minified);
        $result = file_put_contents($target, $minified);

        if ($result === false) {
            LoggerUtil::error("Fallo al escribir el archivo TARGET: $target");
        } else {
            LoggerUtil::info("Archivo minificado creado con éxito: $target (" . $result . " bytes)");
        }
    }
    
    $finalPath = $target . '?v=' . (file_exists($target) ? filemtime($target) : time());
    LoggerUtil::info("Retornando path al HTML: $finalPath");

    // Devolvemos la ruta con un parámetro de versión para evitar caché
    return $finalPath;
}