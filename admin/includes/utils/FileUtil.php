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
            LoggerUtil::error("FILE_UTIL: Error crítico - Estructura de archivo $_FILES corrupta o no válida.");
            return false;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors = [
                UPLOAD_ERR_INI_SIZE => 'Archivo demasiado grande (php.ini)',
                UPLOAD_ERR_FORM_SIZE => 'Archivo demasiado grande (MAX_FILE_SIZE HTML)',
                UPLOAD_ERR_PARTIAL => 'Subida parcial',
                UPLOAD_ERR_NO_FILE => 'No se subió archivo',
                UPLOAD_ERR_NO_TMP_DIR => 'Falta carpeta temporal',
                UPLOAD_ERR_CANT_WRITE => 'Error de escritura en disco',
                UPLOAD_ERR_EXTENSION => 'Extensión PHP detuvo la subida'
            ];
            $msg = $errors[$file['error']] ?? 'Error código: ' . $file['error'];
            LoggerUtil::error("FILE_UTIL: PHP Upload Error: $msg");
            return false;
        }

        // Validar que existe el archivo temporal
        LoggerUtil::info("FILE_UTIL: Verificando archivo temporal: {$file['tmp_name']} (Tamaño: {$file['size']} bytes)");
        if (!is_uploaded_file($file['tmp_name'])) {
            LoggerUtil::error("FILE_UTIL: El archivo temporal no es un archivo subido válido: {$file['tmp_name']}");
            return false;
        }

        
        $dateSubdir = date('Y/m');
        $targetDir = self::$baseUploadPath . $type . '/' . $dateSubdir . '/';
        LoggerUtil::info("FILE_UTIL: Directorio objetivo calculado: $targetDir");

        if (!is_dir($targetDir)) {
            LoggerUtil::info("FILE_UTIL: Creando estructura de directorios: $targetDir");
            if (!mkdir($targetDir, 0755, true)) {
                LoggerUtil::error("FILE_UTIL: Error fatal al crear directorio: $targetDir");
                return false;
            }
            LoggerUtil::info("FILE_UTIL: Directorio creado exitosamente.");
        }

        // Generar nombre único para el archivo
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $fileName = uniqid('fb_', true) . '.' . $extension;
        $targetPath = $targetDir . $fileName;

        LoggerUtil::info("FILE_UTIL: Nombre final generado: $fileName. Procediendo a mover archivo...");

        // Mover el archivo subido a su destino final
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            LoggerUtil::info("FILE_UTIL: Archivo movido a destino. Ajustando permisos chmod 0644.");
            chmod($targetPath, 0644);
            
            $dbPath = 'assets/uploads/' . $type . '/' . $dateSubdir . '/' . $fileName;
            LoggerUtil::info("FILE_UTIL: ¡SUBIDA EXITOSA! Ruta final para DB: $dbPath");
            return $dbPath;
        }
        
        LoggerUtil::error("FILE_UTIL: Fallo crítico al mover de {$file['tmp_name']} a $targetPath. Comprobar permisos de escritura.");
        return false;
    }

    public static function delete(string $relativeFilePath) {
        LoggerUtil::info("FILE_UTIL: Petición de borrado físico: [$relativeFilePath]");
        
        if (empty($relativeFilePath) || $relativeFilePath === "null" || $relativeFilePath === "undefined") {
            LoggerUtil::info("FILE_UTIL: Ruta vacía o inválida recibida. Abortando borrado sin error.");
            return true;
        }
        
        $cleanPath = ltrim(trim($relativeFilePath), './');
        $filePath = __DIR__ . '/../../../' . $cleanPath;
        
        LoggerUtil::info("FILE_UTIL: Resolviendo ruta absoluta para: $filePath");
        $dir = dirname($filePath);
        $base = basename($filePath);
        $realPath = realpath($dir) . '/' . $base;

        // Verificar que el archivo existe
        if (file_exists($realPath) && is_file($realPath)) {
            LoggerUtil::info("FILE_UTIL: Archivo encontrado. Intentando unlink de: $realPath");

            if (unlink($realPath)) {
                LoggerUtil::info("FILE_UTIL: Archivo eliminado correctamente.");
                return true;
            } else {
                LoggerUtil::error("FILE_UTIL: No se pudo eliminar el archivo. Revisar permisos en servidor.");
                return false;
            }
        }

        // Si el archivo no existe consideramos la operación como exitosa (idempotente)
        LoggerUtil::info("FILE_UTIL: El archivo no existe en la ruta física ($realPath). Nada que hacer.");
        return true;
    }
}