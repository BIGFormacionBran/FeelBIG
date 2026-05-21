<?php
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class FileUtil {
    private static $baseUploadPath = __DIR__ . '/../../../../assets/uploads/';

    public static function upload(array $file, string $type = 'images') {
        LoggerUtil::info("FILE_UTIL: Iniciando proceso de upload. Tipo: $type. Nombre original: " . ($file['name'] ?? 'indefinido'));
        
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            LoggerUtil::error("FILE_UTIL: Error de subida PHP: " . ($file['error'] ?? 'No error key'));
            return false;
        }

        $dateFolder = date('Y/m');
        $relativeSubdir = $type . '/' . $dateFolder;
        $targetDir = self::$baseUploadPath . $relativeSubdir . '/';

        LoggerUtil::info("FILE_UTIL: Verificando directorio destino: $targetDir");
        if (!is_dir($targetDir)) {
            LoggerUtil::info("FILE_UTIL: Creando directorio recursivamente...");
            if(!mkdir($targetDir, 0777, true)) {
                LoggerUtil::error("FILE_UTIL: No se pudo crear el directorio $targetDir");
            }
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('fb_', true) . '.' . $extension;
        $targetPath = $targetDir . $fileName;

        LoggerUtil::info("FILE_UTIL: Intentando mover de {$file['tmp_name']} a $targetPath");
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $dbPath = 'assets/uploads/' . $relativeSubdir . '/' . $fileName;
            LoggerUtil::info("FILE_UTIL: Upload EXITOSO. Ruta DB: $dbPath");
            return $dbPath;
        }
        
        LoggerUtil::error("FILE_UTIL: Fallo crítico en move_uploaded_file.");
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