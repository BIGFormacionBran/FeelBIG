<?php
require_once __DIR__ . '/../utils/FileUtil.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class MediaManager {

    public function listPhysicalFiles(string $type = 'images'): array {
        $basePath = __DIR__ . '/../../../assets/uploads/' . $type . '/';
        LoggerUtil::info("MEDIA_MANAGER: Escaneando directorio: $basePath");

        if (!is_dir($basePath)) {
            LoggerUtil::error("MEDIA_MANAGER: Directorio base no existe: $basePath");
            return [];
        }

        $files = [];
        $years = array_diff(scandir($basePath), ['.', '..']);
        LoggerUtil::info("MEDIA_MANAGER: Años encontrados: " . implode(", ", $years));
        
        foreach ($years as $year) {
            $yearPath = $basePath . $year . '/';
            if (!is_dir($yearPath)) {
                LoggerUtil::info("MEDIA_MANAGER: Saltando item (no es dir): $year");
                continue;
            }
            
            $months = array_diff(scandir($yearPath), ['.', '..']);
            foreach ($months as $month) {
                $monthPath = $yearPath . $month . '/';
                if (!is_dir($monthPath)) continue;

                $actualFiles = array_diff(scandir($monthPath), ['.', '..']);
                LoggerUtil::info("MEDIA_MANAGER: Accediendo a $year/$month -> " . count($actualFiles) . " archivos.");

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
        LoggerUtil::info("MEDIA_MANAGER: Escaneo finalizado. Total acumulado: " . count($files) . " archivos.");
        return array_reverse($files); 
    }

    public function uploadContentImage($file) { 
        LoggerUtil::info("MEDIA_MANAGER: Redirigiendo subida de IMAGEN a FileUtil.");
        return FileUtil::upload($file, 'images'); 
    }

    public function uploadContentVideo($file) { 
        LoggerUtil::info("MEDIA_MANAGER: Redirigiendo subida de VIDEO a FileUtil.");
        return FileUtil::upload($file, 'videos'); 
    }
    
    public function deletePhysicalFile($path) {
        LoggerUtil::info("MEDIA_MANAGER: Redirigiendo solicitud de borrado físico a FileUtil. Ruta: $path");
        return FileUtil::delete($path);
    }

    public function deleteContentFiles($imagePath = null, $videoPath = null) {
        LoggerUtil::info("MEDIA_MANAGER: Borrado múltiple iniciado. IMG: [$imagePath], VID: [$videoPath]");
        if ($imagePath) {
            LoggerUtil::info("MEDIA_MANAGER: Borrando imagen...");
            FileUtil::delete($imagePath);
        }
        if ($videoPath) {
            LoggerUtil::info("MEDIA_MANAGER: Borrando vídeo...");
            FileUtil::delete($videoPath);
        }
        return true;
    }
}