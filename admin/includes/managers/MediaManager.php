<?php
require_once __DIR__ . '/../utils/FileUtil.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class MediaManager {

    /**
     * Procesa la subida de una imagen para un contenido
     */
    public function uploadContentImage($file) {
        LoggerUtil::info("MediaManager: Iniciando subida de imagen de contenido");
        return FileUtil::upload($file, 'images');
    }

    /**
     * Procesa la subida de un video para un contenido
     */
    public function uploadContentVideo($file) {
        LoggerUtil::info("MediaManager: Iniciando subida de video de contenido");
        return FileUtil::upload($file, 'videos');
    }

    /**
     * Elimina los archivos asociados a un contenido si se borra el registro
     */
    public function deleteContentFiles($imagePath, $videoPath) {
        $resImg = true;
        $resVid = true;

        if ($imagePath) {
            // Se envía solo la ruta relativa almacenada en la BD
            $resImg = FileUtil::delete($imagePath);
        }
        if ($videoPath) {
            $resVid = FileUtil::delete($videoPath);
        }

        return $resImg && $resVid;
    }
}