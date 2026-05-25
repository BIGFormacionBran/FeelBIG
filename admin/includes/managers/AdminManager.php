<?php
require_once __DIR__ . '/ContentManager.php';
require_once __DIR__ . '/MediaManager.php';
require_once __DIR__ . '/../utils/FileUtil.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class AdminManager {
    public $contents;
    public $media;

    public function __construct() {
        LoggerUtil::info("ADMIN_MANAGER: [INIT] Instanciando AdminManager...");
        $this->contents = new AdminContentManager();
        $this->media = new MediaManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            LoggerUtil::info("ADMIN_MANAGER: [TRIGGER] Petición POST detectada automáticamente en el constructor. Acción solicitada: " . $_POST['action']);
            $this->handleRequest($_POST);
        }
    }

    public function handleRequest(array $postData) {
        $action = $postData['action'] ?? null;
        if (!$action) {
            LoggerUtil::error("ADMIN_MANAGER: [ERROR] Se llamó a handleRequest pero no hay 'action' en el POST.");
            return null;
        }

        LoggerUtil::info("ADMIN_MANAGER: [PROCESO] Ejecutando acción [$action]. Payload: " . json_encode($postData));

        if (in_array($action, ['fm-upload', 'fm-delete-file'])) {
            LoggerUtil::info("ADMIN_MANAGER: [AJAX-INTERCEPT] Acción de File Manager detectada. Limpiando buffers de salida...");
            
            // Limpieza total del buffer para garantizar JSON puro
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            
            header('Content-Type: application/json');
            
            try {
                if ($action === 'fm-upload') {
                    $type = $postData['type'] ?? 'images';
                    
                    if (empty($_FILES['file'])) {
                        LoggerUtil::error("ADMIN_MANAGER: [UPLOAD-ERROR] Archivo no recibido en \$_FILES.");
                        echo json_encode(['success' => false, 'message' => 'No se recibió ningún archivo.']);
                        exit;
                    }

                    $method = ($type === 'videos') ? 'uploadContentVideo' : 'uploadContentImage';
                    $res = $this->media->$method($_FILES['file']);
                    
                    if ($res) {
                        LoggerUtil::info("ADMIN_MANAGER: [UPLOAD-EXITO] Archivo en: $res");
                        echo json_encode(['success' => true, 'path' => $res]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error al procesar la subida física.']);
                    }
                } else {
                    $path = $postData['path'] ?? '';
                    $res = FileUtil::delete($path);
                    if ($res) {
                        LoggerUtil::info("ADMIN_MANAGER: [DELETE-EXITO] Archivo borrado. Limpiando referencias...");
                        $this->contents->clearReferences($path);
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el archivo del servidor.']);
                    }
                }
            } catch (Exception $e) {
                LoggerUtil::error("ADMIN_MANAGER: [AJAX-CRITICAL] " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit; 
        }

        try {
            switch ($action) {
                case 'add':    
                    $res = $this->contents->add($postData);
                    $_GET['status'] = $res ? 'success' : 'error';
                    return $res;

                case 'edit':   
                    $res = $this->contents->save($postData);
                    $_GET['status'] = $res ? 'success' : 'error';
                    return $res;

                case 'delete': 
                    $res = $this->contents->remove($postData);
                    $_GET['status'] = $res ? 'success' : 'error';
                    return $res;

                default: 
                    return null;
            }
        } catch (Exception $e) {
            LoggerUtil::error("ADMIN_MANAGER: [CRUD-CRITICAL] " . $e->getMessage());
            $_GET['status'] = 'error';
            return false;
        }
    }

    public function renderFileManager() {
        $admin = $this; 
        include __DIR__ . '/../components/Files.php';
    }
}