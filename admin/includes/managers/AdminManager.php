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
        $entity = $postData['entity_type'] ?? 'N/A';
        
        if (!$action) {
            LoggerUtil::error("ADMIN_MANAGER: Intento de petición sin acción definida.");
            return null;
        }

        LoggerUtil::info("ADMIN_MANAGER: >>> INICIO REQUEST [$action] para Entidad [$entity]");
        LoggerUtil::info("ADMIN_MANAGER: Datos POST recibidos: " . json_encode($postData));

        try {
            switch ($action) {
                case 'add':    
                    LoggerUtil::info("ADMIN_MANAGER: Delegando creación a ContentManager...");
                    $res = $this->contents->add($postData);
                    LoggerUtil::info("ADMIN_MANAGER: Fin creación. Resultado: " . ($res ? 'ÉXITO' : 'FALLO'));
                    return $res;

                case 'edit':   
                    LoggerUtil::info("ADMIN_MANAGER: Delegando edición a ContentManager...");
                    $res = $this->contents->save($postData);
                    LoggerUtil::info("ADMIN_MANAGER: Fin edición. Resultado: " . ($res ? 'ÉXITO' : 'FALLO'));
                    return $res;

                case 'delete': 
                    LoggerUtil::info("ADMIN_MANAGER: Delegando eliminación a ContentManager...");
                    $res = $this->contents->remove($postData);
                    LoggerUtil::info("ADMIN_MANAGER: Fin eliminación. Resultado: " . ($res ? 'ÉXITO' : 'FALLO'));
                    return $res;
                
                case 'fm-upload':
                    $type = $postData['type'] ?? 'images';
                    LoggerUtil::info("ADMIN_MANAGER: Solicitud File Manager: UPLOAD de tipo [$type]");
                    
                    if (!isset($_FILES['file'])) {
                        LoggerUtil::error("ADMIN_MANAGER: No se encontró el archivo en \$_FILES.");
                        return null;
                    }

                    $method = ($type === 'videos') ? 'uploadContentVideo' : 'uploadContentImage';
                    $res = $this->media->$method($_FILES['file']);
                    LoggerUtil::info("ADMIN_MANAGER: Resultado UPLOAD (Path): " . ($res ?: 'NULL/ERROR'));
                    return $res;

                case 'fm-delete-file':
                    $path = $postData['path'] ?? '';
                    LoggerUtil::info("ADMIN_MANAGER: Solicitud File Manager: BORRADO FÍSICO de [$path]");
                    
                    $res = FileUtil::delete($path);
                    if ($res) {
                        LoggerUtil::info("ADMIN_MANAGER: Borrado físico OK. Procediendo a limpiar referencias en DB...");
                        $cleared = $this->contents->clearReferences($path);
                        LoggerUtil::info("ADMIN_MANAGER: Limpieza de referencias completada: " . ($cleared ? 'SÍ' : 'NO/SIN AFECTAR'));
                    }
                    return $res;

                default: 
                    LoggerUtil::error("ADMIN_MANAGER: Acción desconocida intentada: $action");
                    return null;
            }
        } catch (Exception $e) {
            LoggerUtil::error("ADMIN_MANAGER: ¡CRASH! Excepción: " . $e->getMessage() . " en " . $e->getFile() . ":" . $e->getLine());
            return false;
        }
    }

    public function renderFileManager() {
        LoggerUtil::info("ADMIN_MANAGER: Renderizando componente File Manager (Files.php)");
        $admin = $this; 
        include __DIR__ . '/../components/Files.php';
    }
}