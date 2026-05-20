<?php
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class FileUtil {
    private static $baseUploadPath = __DIR__ . '/../../../assets/uploads/';

    /**
     * Sube un archivo organizándolo en subcarpetas por fecha (año/mes)
     */
    public static function upload(array $file, string $type = 'images') {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $dateFolder = date('Y/m');
        $relativeSubdir = $type . '/' . $dateFolder;
        $targetDir = self::$baseUploadPath . $relativeSubdir . '/';

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('fb_', true) . '.' . $extension;
        $targetPath = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Retornamos la ruta relativa corregida para visualización directa
            return 'assets/uploads/' . $relativeSubdir . '/' . $fileName;
        }

        return false;
    }

    public static function delete(string $relativeFilePath) {
        if (empty($relativeFilePath)) return true;
        
        // Limpiamos el ./ para localizar el archivo en el sistema de archivos
        $cleanPath = ltrim($relativeFilePath, './');
        $filePath = __DIR__ . '/../../../' . $cleanPath;
        
        LoggerUtil::info("Intentando borrar archivo físico en: " . $filePath);

        if (file_exists($filePath)) {
            $res = unlink($filePath);
            LoggerUtil::info($res ? "Borrado exitoso" : "Fallo al borrar unlink");
            return $res;
        }
        
        LoggerUtil::error("Archivo no encontrado para borrar: " . $filePath);
        return true; 
    }
}