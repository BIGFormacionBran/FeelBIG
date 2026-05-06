<?php
// feelbig\includes\utils\config_util.php

class ConfigUtil {
    private static $config = null;

    /**
     * Carga el archivo .env una sola vez y devuelve el valor de la clave solicitada.
     */
    public static function get($key, $default = null) {
        if (self::$config === null) {
            $path = dirname(__DIR__, 2) . '/.env';
            
            if (!file_exists($path)) {
                return $default;
            }

            self::$config = parse_ini_file($path, false, INI_SCANNER_RAW);
        }

        return self::$config[$key] ?? $default;
    }
}