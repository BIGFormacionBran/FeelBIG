<?php

class LoggerUtil {
    private static $logPath = __DIR__ . '/../../logs/';

    public static function log($message, $level = 'INFO') {
        if (!is_dir(self::$logPath)) {
            mkdir(self::$logPath, 0777, true);
        }

        $fileName = self::$logPath . 'system_' . date('Y-m-d') . '.log';
        $timestamp = date('H:i:s');
        $formattedMessage = "[$timestamp] [$level]: $message" . PHP_EOL;

        file_put_contents($fileName, $formattedMessage, FILE_APPEND);
    }

    public static function error($message) {
        self::log($message, 'ERROR');
    }

    public static function info($message) {
        self::log($message, 'INFO');
    }
}