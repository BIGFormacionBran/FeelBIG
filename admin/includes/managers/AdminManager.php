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
            $this->handleRequest($_POST);
        }
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
                    $res = ($type === 'videos') ? $this->media->uploadContentVideo($file) : $this->media->uploadContentImage($file);
                    
                    header('Content-Type: application/json');
                    echo json_encode(['success' => (bool)$res, 'path' => $res, 'message' => $res ? 'Subido' : 'Error']);
                    exit;

                case 'fm-delete-file':
                    $res = $this->media->deletePhysicalFile($postData['path'] ?? '');
                    header('Content-Type: application/json');
                    echo json_encode(['success' => $res]);
                    exit;

                case 'add':
                case 'edit':
                    $res = $this->contents->save($postData);
                    // Como admin.js envía esto vía fetch, respondemos JSON
                    header('Content-Type: application/json');
                    echo json_encode(['success' => (bool)$res]);
                    exit;

                case 'delete': 
                    $res = $this->contents->remove($postData);
                    // Este viene de un formulario normal (ListItems.php), dejamos que continúe para que la página recargue
                    $_GET['status'] = $res ? 'success' : 'error';
                    return $res;

                default: 
                    return null;
            }
        } catch (Exception $e) {
            LoggerUtil::error("ADMIN_MANAGER: Error en $action: " . $e->getMessage());
            if (isset($postData['action']) && strpos($postData['action'], 'fm-') === 0) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
            return false;
        }
    }

    public function renderFileManager() {
        $admin = $this;
        include __DIR__ . '/../components/Files.php';
    }
}