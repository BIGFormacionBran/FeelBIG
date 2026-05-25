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
                    LoggerUtil::info("ADMIN_MANAGER: Procesando fm-upload. Tipo: $type");

                    if (!is_array($file) || !isset($file['error'])) {
                        LoggerUtil::error("ADMIN_MANAGER: Error - El array \$_FILES['file'] no llegó o está incompleto.");
                        $this->sendJson(['success' => false, 'message' => 'No archivo recibido'], 400);
                    }

                    $res = ($type === 'videos') ? $this->media->uploadContentVideo($file) : $this->media->uploadContentImage($file);
                    
                    LoggerUtil::info("ADMIN_MANAGER: Resultado de subida: " . ($res ? "Éxito ($res)" : "Fallo"));
                    $this->sendJson([
                        'success' => (bool)$res, 
                        'path' => $res, 
                        'message' => $res ? 'Subido correctamente' : 'Error en transferencia física'
                    ], $res ? 200 : 400);
                    break;

                case 'fm-delete-file':
                    $path = $postData['path'] ?? '';
                    LoggerUtil::info("ADMIN_MANAGER: Procesando fm-delete-file. Ruta: $path");
                    $res = $this->media->deletePhysicalFile($path);
                    $this->sendJson(['success' => (bool)$res, 'message' => $res ? 'Eliminado' : 'No se pudo eliminar'], $res ? 200 : 400);
                    break;

                case 'add':
                case 'edit':
                    LoggerUtil::info("ADMIN_MANAGER: Procesando guardado de entidad. Acción: $action");
                    // Usamos una única función que determine si es add o edit internamente
                    $res = $this->contents->processSave($postData);
                    $this->sendJson(['success' => (bool)$res, 'message' => $res ? 'Datos guardados' : 'Error en base de datos'], $res ? 200 : 400);
                    break;

                case 'delete': 
                    LoggerUtil::info("ADMIN_MANAGER: Eliminando entidad de DB.");
                    $res = $this->contents->remove($postData);
                    $_GET['status'] = $res ? 'success' : 'error';
                    return $res;

                default: 
                    LoggerUtil::info("ADMIN_MANAGER: Acción [$action] no tiene handler específico.");
                    return null;
            }
        } catch (Exception $e) {
            LoggerUtil::error("ADMIN_MANAGER: EXCEPCIÓN DETECTADA: " . $e->getMessage() . " en " . $e->getFile() . ":" . $e->getLine());
            $this->sendJson(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }

    private function sendJson($data, $code = 200) {
        if (ob_get_length()) {
            LoggerUtil::info("ADMIN_MANAGER: Limpiando buffer de salida (ob_clean). Había contenido previo.");
            ob_clean(); 
        }
        
        if (!headers_sent()) {
            http_response_code($code);
            header('Content-Type: application/json; charset=utf-8');
            LoggerUtil::info("ADMIN_MANAGER: Headers enviados. HTTP $code.");
        } else {
            LoggerUtil::error("ADMIN_MANAGER: Error - Headers ya enviados. Posible salida de texto antes de sendJson.");
        }

        $json = json_encode($data);
        LoggerUtil::info("ADMIN_MANAGER: Payload de respuesta: $json");
        echo $json;
        LoggerUtil::info("ADMIN_MANAGER: Finalizando ejecución (exit).");
        exit;
    }

    public function renderFileManager() {
        LoggerUtil::info("ADMIN_MANAGER: Renderizando componente Files.php");
        $admin = $this;
        include __DIR__ . '/../components/Files.php';
    }
}