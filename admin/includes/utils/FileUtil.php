<?php
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class FileUtil {
    // Ruta base para suidas - apunta a feelbig/assets/uploads/
    private static $baseUploadPath = __DIR__ . '/../../../assets/uploads/';

    public static function upload(array $file, string $type = 'images') {
        $originalName = $file['name'] ?? 'indefinido';
        LoggerUtil::info("FILE_UTIL: Iniciando transferencia física de [$originalName] (Tipo: $type)");
        
        // Validar que el archivo fue subido correctamente
        if (!isset($file['error']) || is_array($file['error'])) {
            LoggerUtil::error("FILE_UTIL: Error crítico de subida PHP - archivo no válido");
            return false;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors = [
                UPLOAD_ERR_INI_SIZE => 'Archivo demasiado grande (INI)',
                UPLOAD_ERR_FORM_SIZE => 'Archivo demasiado grande (FORM)',
                UPLOAD_ERR_PARTIAL => 'Subida parcial',
                UPLOAD_ERR_NO_FILE => 'No se subió archivo',
                UPLOAD_ERR_NO_TMP_DIR => 'Sin directorio temporal',
                UPLOAD_ERR_CANT_WRITE => 'No se puede escribir',
                UPLOAD_ERR_EXTENSION => 'Extensión no permitida'
            ];
            $msg = $errors[$file['error']] ?? 'Error desconocido';
            LoggerUtil::error("FILE_UTIL: Error de subida PHP ($file[error]): $msg");
            return false;
        }

        // Validar que existe el archivo temporal
        if (!is_uploaded_file($file['tmp_name'])) {
            LoggerUtil::error("FILE_UTIL: El archivo temporal no es un archivo subido válido: {$file['tmp_name']}");
            return false;
        }

        // Crear estructura de directorios por fecha
        $dateSubdir = date('Y/m');
        $targetDir = self::$baseUploadPath . $type . '/' . $dateSubdir . '/';

        if (!is_dir($targetDir)) {
            LoggerUtil::info("FILE_UTIL: Creando estructura de directorios: $targetDir");
            if (!mkdir($targetDir, 0755, true)) {
                LoggerUtil::error("FILE_UTIL: No se pudo crear el directorio destino: $targetDir");
                return false;
            }
        }

        // Generar nombre único para el archivo
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $fileName = uniqid('fb_', true) . '.' . $extension;
        $targetPath = $targetDir . $fileName;

        LoggerUtil::info("FILE_UTIL: Intentando move_uploaded_file de [{$file['tmp_name']}] a [$targetPath]");

        // Mover el archivo subido a su destino final
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Asegurar permisos legibles
            chmod($targetPath, 0644);
            
            $dbPath = 'assets/uploads/' . $type . '/' . $dateSubdir . '/' . $fileName;
            LoggerUtil::info("FILE_UTIL: ¡SUBIDA EXITOSA! Ruta relativa generada: $dbPath");
            return $dbPath;
        }
        
        LoggerUtil::error("FILE_UTIL: Fallo al mover el archivo temporal al destino final.");
        return false;
    }

    public static function delete(string $relativeFilePath) {
        LoggerUtil::info("FILE_UTIL: Iniciando borrado físico para: [$relativeFilePath]");
        
        if (empty($relativeFilePath) || $relativeFilePath === "null" || $relativeFilePath === "undefined") {
            LoggerUtil::info("FILE_UTIL: Ruta recibida vacía/nula. Se marca como completado (nada que borrar).");
            return true;
        }
        
        // Limpiar la ruta de caracteres innecesarios
        $cleanPath = trim($relativeFilePath);
        $cleanPath = ltrim($cleanPath, './');
        
        // Construir ruta absoluta desde la raíz del proyecto
        $filePath = __DIR__ . '/../../../' . $cleanPath;
        
        // Normalizar la ruta (resolver ., .., etc.)
        $realPath = realpath(dirname($filePath)) . '/' . basename($filePath);

        LoggerUtil::info("FILE_UTIL: Ruta limpia: [$cleanPath] -> Ruta absoluta: [$realPath]");

        // Verificar que el archivo existe
        if (file_exists($realPath) && is_file($realPath)) {
            LoggerUtil::info("FILE_UTIL: Archivo localizado en disco: [$realPath]. Ejecutando unlink...");
            
            if (unlink($realPath)) {
                LoggerUtil::info("FILE_UTIL: Archivo eliminado físicamente del servidor.");
                return true;
            } else {
                LoggerUtil::error("FILE_UTIL: Fallo al ejecutar unlink sobre el archivo.");
                return false;
            }
        }
        
        LoggerUtil::error("FILE_UTIL: El archivo no existe o no es accesible en [$realPath].");
        return false;
    }
}