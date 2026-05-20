<?php
require_once __DIR__ . '/../utils/FileUtil.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class MediaManager {
    /**
     * Lista los archivos físicos en el servidor según el tipo
     */
    public function listPhysicalFiles(string $type = 'images'): array {
        $basePath = __DIR__ . '/../../../assets/uploads/' . $type . '/';
        if (!is_dir($basePath)) return [];

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
                foreach ($actualFiles as $f) {
                    $pathForDb = './assets/uploads/' . $type . '/' . $year . '/' . $month . '/' . $f;
                    $files[] = [
                        'name' => $f,
                        'path' => $pathForDb,
                        'url'  => $pathForDb,
                        'date' => $year . '-' . $month
                    ];
                }
            }
        }
        return array_reverse($files); 
    }

    public function uploadContentImage($file) { 
        LoggerUtil::info("Subiendo imagen...");
        return FileUtil::upload($file, 'images'); 
    }

    public function uploadContentVideo($file) { 
        LoggerUtil::info("Subiendo video...");
        return FileUtil::upload($file, 'videos'); 
    }
    
    public function deleteContentFiles($imagePath = null, $videoPath = null) {
        if ($imagePath) FileUtil::delete($imagePath);
        if ($videoPath) FileUtil::delete($videoPath);
        return true;
    }
}