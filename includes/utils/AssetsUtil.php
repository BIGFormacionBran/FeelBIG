<?php

function getMinifiedCssUtil($filename = 'styles', $dir = 'assets/css') {
    $dir = rtrim($dir, '/') . '/';
    $source = $dir . "{$filename}.css";
    $target = $dir . "{$filename}.min.css";

    if (!file_exists($source)) return '';

    if (!file_exists($target) || filemtime($source) > filemtime($target)) {
        $content = file_get_contents($source);
        if ($content !== false) {
            // Minificación rápida
            $minified = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
            $minified = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    '], '', $minified);
            file_put_contents($target, $minified);
        }
    }
    
    return $target . '?v=' . (file_exists($target) ? filemtime($target) : time());
}