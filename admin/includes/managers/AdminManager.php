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

        LoggerUtil::info("ADMIN_MANAGER: [PROCESO] Ejecutando acción [$action]");

        try {
            switch ($action) {
                case 'fm-upload':
                    $type = $postData['type'] ?? 'images';
                    $file = $_FILES['file'] ?? null;
                    if (!is_array($file) || !isset($file['error'])) {
                        $this->sendJson(['success' => false, 'message' => 'No archivo recibido'], 400);
                    }
                    $res = ($type === 'videos') ? $this->media->uploadContentVideo($file) : $this->media->uploadContentImage($file);
                    $this->sendJson(['success' => (bool)$res, 'path' => $res, 'message' => $res ? 'Subido' : 'Error en subida'], $res ? 200 : 400);
                    break;

                case 'fm-delete-file':
                    $path = $postData['path'] ?? '';
                    $res = $this->media->deletePhysicalFile($path);
                    $this->sendJson(['success' => (bool)$res, 'message' => $res ? 'Eliminado' : 'Error al eliminar'], $res ? 200 : 400);
                    break;

                case 'add':
                case 'edit':
                    $res = $this->contents->save($postData);
                    $this->sendJson(['success' => (bool)$res, 'message' => $res ? 'Guardado' : 'Error al guardar'], $res ? 200 : 400);
                    break;

                case 'delete': 
                    $res = $this->contents->remove($postData);
                    $_GET['status'] = $res ? 'success' : 'error';
                    return $res;

                default: 
                    return null;
            }
        } catch (Exception $e) {
            LoggerUtil::error("ADMIN_MANAGER: Error en $action: " . $e->getMessage());
            $this->sendJson(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    private function sendJson($data, $code = 200) {
        while (ob_get_level() > 0) ob_end_clean();
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    public function renderFileManager() {
        $admin = $this;
        include __DIR__ . '/../components/Files.php';
    }
}