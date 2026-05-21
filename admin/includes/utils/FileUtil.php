<?php
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class FileUtil {
    private static $baseUploadPath = __DIR__ . '/../../../../assets/uploads/';

    public static function upload(array $file, string $type = 'images') {
        LoggerUtil::info("FILE_UTIL: Procesando subida física. Nombre: " . ($file['name'] ?? '??'));
        
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            LoggerUtil::error("FILE_UTIL: Error PHP UPLOAD_ERR: " . ($file['error'] ?? 'no_key'));
            return false;
        }

        $targetDir = self::$baseUploadPath . $type . '/' . date('Y/m') . '/';
        if (!is_dir($targetDir)) {
            LoggerUtil::info("FILE_UTIL: Creando directorio $targetDir");
            mkdir($targetDir, 0777, true);
        }

        $fileName = uniqid('fb_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $targetPath = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $dbPath = 'assets/uploads/' . $type . '/' . date('Y/m') . '/' . $fileName;
            LoggerUtil::info("FILE_UTIL: ¡EXITO! Archivo en: $dbPath");
            return $dbPath;
        }
        
        LoggerUtil::error("FILE_UTIL: ERROR al mover archivo a $targetPath");
        return false;
    }

    public static function delete(string $relativeFilePath) {
        LoggerUtil::info("FILE_UTIL: Solicitud de borrado para: $relativeFilePath");
        if (empty($relativeFilePath)) {
            LoggerUtil::info("FILE_UTIL: Ruta vacía, ignorando borrado.");
            return true;
        }
        
        $cleanPath = ltrim($relativeFilePath, './');
        $filePath = __DIR__ . '/../../../../' . $cleanPath;
        LoggerUtil::info("FILE_UTIL: Ruta física absoluta resuelta: $filePath");

        if (file_exists($filePath)) {
            $res = unlink($filePath);
            LoggerUtil::info("FILE_UTIL: Resultado del borrado (unlink): " . ($res ? 'ÉXITO' : 'FALLO'));
            return $res;
        }
        
        LoggerUtil::error("FILE_UTIL: El archivo no existe en el disco: $filePath");
        return true; 
    }
}