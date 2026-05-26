<?php
require_once __DIR__ . '/ContentManager.php';
require_once __DIR__ . '/UserManager.php';
require_once __DIR__ . '/MediaManager.php';
require_once __DIR__ . '/../utils/FileUtil.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class AdminManager {
    public $contents;
    public $users;
    public $media;

    public function __construct() {
        $this->contents = new AdminContentManager();
        $this->users = new AdminUserManager();
        $this->media = new MediaManager();
    }

    public function handleRequest(array $postData) {
        $action = $postData['action'] ?? null;
        if (!$action) {
            LoggerUtil::info("ADMIN_MANAGER: Request recibido sin 'action'. Saltando procesamiento.");
            return null;
        }

        LoggerUtil::info("ADMIN_MANAGER: [INICIO ACCIÓN] -> $action");
        LoggerUtil::info("ADMIN_MANAGER: POST Payload: " . json_encode($postData));

        try {
            switch ($action) {
                case 'fm-upload':
                    $type = $postData['type'] ?? 'images';
                    $file = $_FILES['file'] ?? null;
                    if (!is_array($file) || !isset($file['error'])) {
                        $this->sendJson(['success' => false, 'message' => 'No archivo recibido'], 400);
                    }
                    $res = ($type === 'videos') ? $this->media->uploadContentVideo($file) : $this->media->uploadContentImage($file);
                    $this->sendJson(['success' => (bool)$res, 'path' => $res, 'message' => $res ? 'Subido correctamente' : 'Error en transferencia'], $res ? 200 : 400);
                    break;

                case 'fm-delete-file':
                    $path = $postData['path'] ?? '';
                    $res = $this->media->deletePhysicalFile($path);
                    $this->sendJson(['success' => (bool)$res, 'message' => $res ? 'Archivo eliminado' : 'Error al eliminar'], $res ? 200 : 400);
                    break;

                case 'add':
                case 'edit':
                    if (($postData['entity_type'] ?? '') === 'Usuario') {
                        $res = $this->users->save($postData);
                    } else {
                        $res = $this->contents->processSave($postData);
                    }
                    $this->sendJson(['success' => (bool)$res, 'message' => $res ? 'Guardado correctamente' : 'Error en DB'], $res ? 200 : 400);
                    break;

                case 'delete': 
                    if (($postData['entity_type'] ?? '') === 'Usuario') {
                        // Los usuarios no se eliminan
                        return false;
                    } else {
                        $res = $this->contents->remove($postData);
                        $_GET['status'] = $res ? 'success' : 'error';
                        return $res;
                    }

                default: 
                    return null;
            }
        } catch (Exception $e) {
            LoggerUtil::error("ADMIN_MANAGER: EXCEPCIÓN: " . $e->getMessage());
            $this->sendJson(['success' => false, 'message' => 'Error interno'], 500);
        }
    }

    private function sendJson($data, $code = 200) {
        if (ob_get_length()) ob_clean(); 
        if (!headers_sent()) {
            http_response_code($code);
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode($data);
        exit;
    }

    public function renderFileManager() {
        $admin = $this;
        include __DIR__ . '/../components/Files.php';
    }
}