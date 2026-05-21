<?php
require_once __DIR__ . '/ContentManager.php';
require_once __DIR__ . '/MediaManager.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class AdminManager {
    public $contents;
    public $media;

    public function __construct() {
        LoggerUtil::info("ADMIN_MANAGER: Inicializando componentes...");
        $this->contents = new AdminContentManager();
        $this->media = new MediaManager();
    }

    public function renderFileManager() {
        $file = __DIR__ . '/../components/Files.php';
        LoggerUtil::info("ADMIN_UI: Cargando FileManager desde $file");
        if (file_exists($file)) include $file;
        else LoggerUtil::error("ADMIN_UI: No se encontró el componente Files.php");
    }

    public function handleRequest(array $postData) {
        $action = $postData['action'] ?? null;
        LoggerUtil::info("ADMIN_REQUEST: Acción recibida [$action]. Datos: " . json_encode($postData));

        if (!$action) {
            LoggerUtil::error("ADMIN_REQUEST: Intento de request sin acción definida.");
            return null;
        }

        switch ($action) {
            case 'add':
                LoggerUtil::info("ADMIN_FLOW: Ejecutando ADD para entidad " . ($postData['entity_type'] ?? 'desconocida'));
                return $this->contents->add($postData);
            case 'edit':
                LoggerUtil::info("ADMIN_FLOW: Ejecutando EDIT para ID: " . ($postData['id'] ?? 'N/A'));
                return $this->contents->save($postData);
            case 'delete':
                LoggerUtil::info("ADMIN_FLOW: Ejecutando DELETE para ID: " . ($postData['id'] ?? 'N/A'));
                return $this->contents->remove($postData);
            
            case 'fm-upload':
                $type = $postData['type'] ?? 'images';
                LoggerUtil::info("ADMIN_FLOW: Subida FileManager tipo [$type]");
                $method = ($type === 'videos') ? 'uploadContentVideo' : 'uploadContentImage';
                $res = $this->media->$method($_FILES['file']);
                LoggerUtil::info("ADMIN_FLOW: Resultado subida: " . ($res ?: 'FALLO'));
                return $res;

            case 'fm-delete-file':
                $path = $postData['path'] ?? '';
                LoggerUtil::info("ADMIN_FLOW: Borrado físico FileManager solicitado para: $path");
                if (empty($path)) {
                    LoggerUtil::error("ADMIN_FLOW: Ruta vacía recibida en fm-delete-file");
                    return false;
                }
                $res = FileUtil::delete($path);
                if ($res) {
                    LoggerUtil::info("ADMIN_FLOW: Borrado exitoso. Limpiando referencias en DB...");
                    $this->contents->clearReferences($path);
                } else {
                    LoggerUtil::error("ADMIN_FLOW: Falló el borrado físico de $path");
                }
                return $res;

            default:
                LoggerUtil::error("ADMIN_REQUEST: Acción [$action] no mapeada en handleRequest");
                return null;
        }
    }
}