<?php
function getMinifiedCssUtil($filename = 'styles') {
    $source = "assets/css/{$filename}.css";
    $target = "assets/css/{$filename}.min.css";

    if (!file_exists($source)) return '';

    if (!file_exists($target) || filemtime($source) > filemtime($target)) {
        file_put_contents($target, (str_replace(["\r\n", "\r", "\n", "\t", '  ', '    '], '', preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', file_get_contents($source)))));
    }
    return $target . '?v=' . filemtime($target);
}