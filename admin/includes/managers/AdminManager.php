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
    }

    public function handleRequest(array $postData) {
        $action = $postData['action'] ?? null;
        if (!$action) return null;

        LoggerUtil::info("ADMIN_MANAGER: [PROCESO] Ejecutando acción [$action]");

        try {
            switch ($action) {
                case 'fm-upload':
                    $type = $postData['type'] ?? 'images';
                    $file = $_FILES['file'] ?? null;
                    
                    if (!is_array($file) || !isset($file['error'])) {
                        LoggerUtil::error("ADMIN_MANAGER: No se recibió archivo válido en fm-upload");
                        if (ob_get_level() > 0) ob_end_clean();
                        header('Content-Type: application/json; charset=utf-8');
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'No archivo recibido']);
                        exit(1);
                    }
                    
                    $res = ($type === 'videos') ? $this->media->uploadContentVideo($file) : $this->media->uploadContentImage($file);
                    
                    if (ob_get_level() > 0) ob_end_clean();
                    header('Content-Type: application/json; charset=utf-8');
                    http_response_code($res ? 200 : 400);
                    echo json_encode(['success' => (bool)$res, 'path' => $res, 'message' => $res ? 'Subido' : 'Error en subida']);
                    exit(0);

                case 'fm-delete-file':
                    $path = $postData['path'] ?? '';
                    LoggerUtil::info("ADMIN_MANAGER: Eliminando archivo: $path");
                    
                    $res = $this->media->deletePhysicalFile($path);
                    if (ob_get_level() > 0) ob_end_clean();
                    header('Content-Type: application/json; charset=utf-8');
                    http_response_code($res ? 200 : 400);
                    echo json_encode(['success' => (bool)$res, 'message' => $res ? 'Eliminado' : 'Error al eliminar']);
                    exit(0);

                case 'add':
                case 'edit':
                    $res = $this->contents->save($postData);
                    if (ob_get_level() > 0) ob_end_clean();
                    header('Content-Type: application/json; charset=utf-8');
                    http_response_code($res ? 200 : 400);
                    echo json_encode(['success' => (bool)$res, 'message' => $res ? 'Guardado' : 'Error al guardar']);
                    exit(0);

                case 'delete': 
                    $res = $this->contents->remove($postData);
                    $_GET['status'] = $res ? 'success' : 'error';
                    return $res;

                default: 
                    return null;
            }
        } catch (Exception $e) {
            LoggerUtil::error("ADMIN_MANAGER: Error en $action: " . $e->getMessage());
            if (ob_get_level() > 0) ob_end_clean();
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
            exit(1);
        }
    }

    public function renderFileManager() {
        $admin = $this;
        include __DIR__ . '/../components/Files.php';
    }
}