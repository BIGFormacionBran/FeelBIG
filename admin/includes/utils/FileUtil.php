<?php
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class FileUtil {
    // Ajustado para llegar correctamente a assets/uploads desde admin/includes/utils
    private static $baseUploadPath = __DIR__ . '/../../../../assets/uploads/';

    public static function upload(array $file, string $type = 'images') {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) return false;

        $dateFolder = date('Y/m');
        $relativeSubdir = $type . '/' . $dateFolder;
        $targetDir = self::$baseUploadPath . $relativeSubdir . '/';

        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('fb_', true) . '.' . $extension;
        $targetPath = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'assets/uploads/' . $relativeSubdir . '/' . $fileName;
        }
        return false;
    }

    public static function delete(string $relativeFilePath) {
        if (empty($relativeFilePath)) return true;
        
        $cleanPath = ltrim($relativeFilePath, './');
        // Ruta absoluta corregida: de admin/includes/utils/ a htdocs/
        $filePath = __DIR__ . '/../../../../' . $cleanPath;

        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return true; 
    }
}