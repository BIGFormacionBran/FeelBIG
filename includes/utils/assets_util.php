<?php
function get_minified_css_util() {
    $source = 'assets/css/styles.css';
    $target = 'assets/css/styles.min.css';

    if (!file_exists($source)) return '';

    if (!file_exists($target) || filemtime($source) > filemtime($target)) {
        $css = file_get_contents($source);
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    '], '', $css);
        file_put_contents($target, $css);
    }
    return $target . '?v=' . filemtime($target);
}