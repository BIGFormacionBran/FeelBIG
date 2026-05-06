<?php
// feelbig\includes\utils\config_util.php

class ConfigUtil {
    private static $config = null;

    /**
     * Carga el archivo .env manualmente para evitar problemas con caracteres especiales 
     * en contraseñas (como @, !, -, etc.) que parse_ini_file puede interpretar mal.
     */
    public static function get($key, $default = null) {
        if (self::$config === null) {
            $path = dirname(__DIR__, 2) . '/.env';
            self::$config = [];

            if (!file_exists($path)) {
                return $default;
            }

            // Leemos el archivo línea por línea para capturar valores literales
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                // Ignorar comentarios
                if (strpos(trim($line), '#') === 0) continue;

                // Dividir por el primer signo '='
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);

                // Quitar comillas si existen (al principio y al final)
                $value = trim($value, '"\'');

                self::$config[$name] = $value;
            }
        }

        return self::$config[$key] ?? $default;
    }
}