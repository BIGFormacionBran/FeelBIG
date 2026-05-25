<?php
require_once __DIR__ . '/../utils/FileUtil.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class MediaManager {

    public function listPhysicalFiles(string $type = 'images'): array {
        $basePath = __DIR__ . '/../../../assets/uploads/' . $type . '/';
        LoggerUtil::info("MEDIA_MANAGER: Escaneando archivos físicos tipo [$type] en [$basePath]");

        if (!is_dir($basePath)) {
            LoggerUtil::error("MEDIA_MANAGER: Directorio no existe: $basePath");
            return [];
        }

        $files = [];
        $years = array_diff(scandir($basePath), ['.', '..']);
        
        foreach ($years as $year) {
            $yearPath = $basePath . $year . '/';
            if (!is_dir($yearPath)) continue;
            
            $months = array_diff(scandir($yearPath), ['.', '..']);
            foreach ($months as $month) {
                $monthPath = $yearPath . $month . '/';
                if (!is_dir($monthPath)) continue;

                $actualFiles = array_diff(scandir($monthPath), ['.', '..']);
                LoggerUtil::info("MEDIA_MANAGER: Carpeta $year/$month -> " . count($actualFiles) . " archivos encontrados.");

                foreach ($actualFiles as $f) {
                    $pathForDb = 'assets/uploads/' . $type . '/' . $year . '/' . $month . '/' . $f;
                    $files[] = [
                        'name' => $f,
                        'path' => $pathForDb,
                        'url'  => $pathForDb,
                        'date' => $year . '-' . $month
                    ];
                }
            }
        }
        LoggerUtil::info("MEDIA_MANAGER: Escaneo completado. Total: " . count($files) . " archivos.");
        return array_reverse($files); 
    }

    public function uploadContentImage($file) { 
        LoggerUtil::info("MEDIA_MANAGER: Petición de subida de IMAGEN detectada.");
        return FileUtil::upload($file, 'images'); 
    }

    public function uploadContentVideo($file) { 
        LoggerUtil::info("MEDIA_MANAGER: Petición de subida de VIDEO detectada.");
        return FileUtil::upload($file, 'videos'); 
    }
    
    // Nuevo método para borrar un archivo físico directamente por su ruta
    public function deletePhysicalFile($path) {
        LoggerUtil::info("MEDIA_MANAGER: Borrado directo solicitado para: $path");
        return FileUtil::delete($path);
    }

    public function deleteContentFiles($imagePath = null, $videoPath = null) {
        LoggerUtil::info("MEDIA_MANAGER: Solicitud de borrado de archivos específicos: IMG[$imagePath], VID[$videoPath]");
        if ($imagePath) FileUtil::delete($imagePath);
        if ($videoPath) FileUtil::delete($videoPath);
        return true;
    }
}