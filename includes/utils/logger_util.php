<?php

class Logger {
    private static $logPath = __DIR__ . '/../../logs/';

    /**
     * Escribe un mensaje en el archivo de log del sistema
     * @param string $message Mensaje a guardar
     * @param string $level Nivel (INFO, ERROR, WARNING)
     */
    public static function log($message, $level = 'INFO') {
        if (!is_dir(self::$logPath)) {
            mkdir(self::$logPath, 0777, true);
        }

        $fileName = self::$logPath . 'system_' . date('Y-m-d') . '.log';
        $timestamp = date('H:i:s');
        $formattedMessage = "[$timestamp] [$level]: $message" . PHP_EOL;

        // Escribimos al archivo (FILE_APPEND para no borrar lo anterior)
        file_put_contents($fileName, $formattedMessage, FILE_APPEND);
    }

    public static function error($message) {
        self::log($message, 'ERROR');
    }

    public static function info($message) {
        self::log($message, 'INFO');
    }
}