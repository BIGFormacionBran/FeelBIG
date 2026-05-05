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
                // Si no existe, podrías lanzar una excepción o registrar un error
                return $default;
            }

            // Usamos parse_ini_file que es lo que ya estabas usando y funciona bien
            self::$config = parse_ini_file($path);
        }

        return self::$config[$key] ?? $default;
    }
}