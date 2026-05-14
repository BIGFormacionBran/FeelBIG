<?php

class ConfigUtil {
    private static $config = null;

    public static function get($key, $default = null) {
        if (self::$config === null) {
            $path = dirname(__DIR__, 2) . '/.env';
            self::$config = [];

            if (!file_exists($path)) {
                return $default;
            }

            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;

                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                $value = trim($value, '"\'');

                self::$config[$name] = $value;
            }
        }

        return self::$config[$key] ?? $default;
    }
}