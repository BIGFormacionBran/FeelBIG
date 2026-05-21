<?php
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class FileUtil {
    // Corregida la ruta base para que apunte correctamente a la raíz/assets
    private static $baseUploadPath = __DIR__ . '/../../../assets/uploads/';

    public static function upload(array $file, string $type = 'images') {
        $originalName = $file['name'] ?? 'indefinido';
        LoggerUtil::info("FILE_UTIL: Iniciando transferencia física de [$originalName] (Tipo: $type)");
        
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            LoggerUtil::error("FILE_UTIL: Error crítico de subida PHP. Código: " . ($file['error'] ?? 'desconocido'));
            return false;
        }

        $dateSubdir = date('Y/m');
        $targetDir = self::$baseUploadPath . $type . '/' . $dateSubdir . '/';

        if (!is_dir($targetDir)) {
            LoggerUtil::info("FILE_UTIL: Creando estructura de directorios: $targetDir");
            if (!mkdir($targetDir, 0777, true)) {
                LoggerUtil::error("FILE_UTIL: No se pudo crear el directorio destino.");
                return false;
            }
        }

        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $fileName = uniqid('fb_', true) . '.' . $extension;
        $targetPath = $targetDir . $fileName;

        LoggerUtil::info("FILE_UTIL: Intentando move_uploaded_file de [{$file['tmp_name']}] a [$targetPath]");

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $dbPath = 'assets/uploads/' . $type . '/' . $dateSubdir . '/' . $fileName;
            LoggerUtil::info("FILE_UTIL: ¡SUBIDA EXITOSA! Ruta relativa generada: $dbPath");
            return $dbPath;
        }
        
        LoggerUtil::error("FILE_UTIL: Fallo al mover el archivo al destino final.");
        return false;
    }

    public static function delete(string $relativeFilePath) {
        LoggerUtil::info("FILE_UTIL: Iniciando borrado físico para: [$relativeFilePath]");
        
        if (empty($relativeFilePath)) {
            LoggerUtil::info("FILE_UTIL: Ruta recibida vacía. Se marca como completado (nada que borrar).");
            return true;
        }
        
        $cleanPath = ltrim($relativeFilePath, './');
        $filePath = __DIR__ . '/../../../' . $cleanPath; // Ajustada ruta de borrado

        if (file_exists($filePath)) {
            LoggerUtil::info("FILE_UTIL: Archivo localizado en disco: [$filePath]. Ejecutando unlink...");
            $res = unlink($filePath);
            if ($res) {
                LoggerUtil::info("FILE_UTIL: Archivo eliminado físicamente del servidor.");
            } else {
                LoggerUtil::error("FILE_UTIL: Fallo al ejecutar unlink sobre el archivo.");
            }
            return $res;
        }
        
        LoggerUtil::error("FILE_UTIL: El archivo no existe físicamente en [$filePath]. Se omite el borrado.");
        return true; 
    }
}