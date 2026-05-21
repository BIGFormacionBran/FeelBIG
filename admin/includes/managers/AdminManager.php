<?php
require_once __DIR__ . '/ContentManager.php';
require_once __DIR__ . '/MediaManager.php';
require_once __DIR__ . '/../utils/FileUtil.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class AdminManager {
    public $contents;
    public $media;

    public function __construct() {
        $this->contents = new AdminContentManager();
        $this->media = new MediaManager();
    }

    public function handleRequest(array $postData) {
        $action = $postData['action'] ?? null;
        if (!$action) return null;

        LoggerUtil::info("ADMIN_MANAGER: Procesando acción [$action] con datos: " . json_encode($postData));

        try {
            switch ($action) {
                case 'add':    
                    $res = $this->contents->add($postData);
                    LoggerUtil::info("ADMIN_MANAGER: Resultado add: " . ($res ? 'OK' : 'FALLO'));
                    return $res;

                case 'edit':   
                    $res = $this->contents->save($postData);
                    LoggerUtil::info("ADMIN_MANAGER: Resultado edit: " . ($res ? 'OK' : 'FALLO'));
                    return $res;

                case 'delete': 
                    $res = $this->contents->remove($postData);
                    LoggerUtil::info("ADMIN_MANAGER: Resultado delete: " . ($res ? 'OK' : 'FALLO'));
                    return $res;
                
                case 'fm-upload':
                    $type = $postData['type'] ?? 'images';
                    LoggerUtil::info("ADMIN_MANAGER: Iniciando upload media tipo: $type");
                    $method = ($type === 'videos') ? 'uploadContentVideo' : 'uploadContentImage';
                    $res = $this->media->$method($_FILES['file'] ?? []);
                    LoggerUtil::info("ADMIN_MANAGER: Resultado upload path: " . ($res ?: 'NULL'));
                    return $res;

                case 'fm-delete-file':
                    $path = $postData['path'] ?? '';
                    LoggerUtil::info("ADMIN_MANAGER: Intento borrado físico de: $path");
                    $res = FileUtil::delete($path);
                    if ($res) {
                        LoggerUtil::info("ADMIN_MANAGER: Borrado físico OK, limpiando referencias en DB...");
                        $this->contents->clearReferences($path);
                    }
                    return $res;

                default: 
                    LoggerUtil::error("ADMIN_MANAGER: Acción desconocida: $action");
                    return null;
            }
        } catch (Exception $e) {
            LoggerUtil::error("ADMIN_MANAGER: EXCEPCIÓN: " . $e->getMessage());
            return false;
        }
    }

    public function renderFileManager() {
        $admin = $this; 
        include __DIR__ . '/../components/Files.php';
    }
}