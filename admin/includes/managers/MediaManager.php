<?php
require_once __DIR__ . '/../utils/FileUtil.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class MediaManager {

    /**
     * Procesa la subida de una imagen para un contenido
     */
    public function uploadContentImage($file) {
        LoggerUtil::info("MediaManager: Iniciando subida de imagen de contenido");
        return FileUtil::upload($file, 'contents/images');
    }

    /**
     * Procesa la subida de un video para un contenido
     */
    public function uploadContentVideo($file) {
        LoggerUtil::info("MediaManager: Iniciando subida de video de contenido");
        return FileUtil::upload($file, 'contents/videos');
    }

    /**
     * Elimina los archivos asociados a un contenido si se borra el registro
     */
    public function deleteContentFiles($imageName, $videoName) {
        $resImg = true;
        $resVid = true;

        if ($imageName) {
            // Quitamos el segundo argumento porque FileUtil::delete no lo recibe
            $resImg = FileUtil::delete($imageName);
        }
        if ($videoName) {
            $resVid = FileUtil::delete($videoName);
        }

        return $resImg && $resVid;
    }
}