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

        // EL SALVAVIDAS: Auto-captura de POST para que las vistas no tengan lógica basura.
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

        // =========================================================================
        // INTERCEPTOR AJAX PARA EL FILE MANAGER (ELIMINA EL ERROR DEL <!DOCTYPE)
        // =========================================================================
        if (in_array($action, ['fm-upload', 'fm-delete-file'])) {
            LoggerUtil::info("ADMIN_MANAGER: [AJAX-INTERCEPT] Acción de File Manager detectada. Limpiando buffers de salida...");
            while (ob_get_level()) ob_end_clean(); // BORRA CUALQUIER HTML BASURA QUE SE HAYA COLADO
            header('Content-Type: application/json');
            
            try {
                if ($action === 'fm-upload') {
                    $type = $postData['type'] ?? 'images';
                    LoggerUtil::info("ADMIN_MANAGER: [UPLOAD] Solicitud para subir archivo tipo: $type");
                    
                    if (empty($_FILES['file'])) {
                        LoggerUtil::error("ADMIN_MANAGER: [UPLOAD-ERROR] La variable \$_FILES['file'] está vacía. Verifica el FormData en JS.");
                        echo json_encode(['success' => false, 'message' => 'No se recibió ningún archivo.']);
                        exit;
                    }

                    $method = ($type === 'videos') ? 'uploadContentVideo' : 'uploadContentImage';
                    $res = $this->media->$method($_FILES['file']);
                    
                    if ($res) {
                        LoggerUtil::info("ADMIN_MANAGER: [UPLOAD-EXITO] Archivo subido y registrado en: $res");
                        echo json_encode(['success' => true, 'path' => $res]);
                    } else {
                        LoggerUtil::error("ADMIN_MANAGER: [UPLOAD-ERROR] El MediaManager devolvió false al subir.");
                        echo json_encode(['success' => false, 'message' => 'Error al mover el archivo físico.']);
                    }
                } else {
                    $path = $postData['path'] ?? '';
                    LoggerUtil::info("ADMIN_MANAGER: [DELETE] Solicitud para borrar archivo físico en: $path");
                    $res = FileUtil::delete($path);
                    if ($res) {
                        LoggerUtil::info("ADMIN_MANAGER: [DELETE-EXITO] Archivo borrado. Procediendo a limpiar BD...");
                        $this->contents->clearReferences($path);
                        echo json_encode(['success' => true]);
                    } else {
                        LoggerUtil::error("ADMIN_MANAGER: [DELETE-ERROR] FileUtil devolvió false al borrar.");
                        echo json_encode(['success' => false, 'message' => 'No se pudo borrar el archivo físico.']);
                    }
                }
            } catch (Exception $e) {
                LoggerUtil::error("ADMIN_MANAGER: [AJAX-CRITICAL] Excepción capturada: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Error interno del servidor.']);
            }
            LoggerUtil::info("ADMIN_MANAGER: [AJAX-END] Finalizando script con exit() para devolver JSON puro al JS.");
            exit; // FIN DE LA EJECUCIÓN. EL JS RECIBE SU JSON Y NO CRASHEA.
        }

        // =========================================================================
        // ACCIONES ESTÁNDAR (FORMULARIOS DE GUARDAR, EDITAR, ELIMINAR)
        // =========================================================================
        try {
            switch ($action) {
                case 'add':    
                    LoggerUtil::info("ADMIN_MANAGER: [CRUD] Delegando creación a ContentManager...");
                    $res = $this->contents->add($postData);
                    LoggerUtil::info("ADMIN_MANAGER: [CRUD] Resultado creación: " . ($res ? 'ÉXITO' : 'FALLO'));
                    $_GET['status'] = $res ? 'success' : 'error'; // Inyección para Alerts.php
                    return $res;

                case 'edit':   
                    LoggerUtil::info("ADMIN_MANAGER: [CRUD] Delegando edición a ContentManager...");
                    $res = $this->contents->save($postData);
                    LoggerUtil::info("ADMIN_MANAGER: [CRUD] Resultado edición: " . ($res ? 'ÉXITO' : 'FALLO'));
                    $_GET['status'] = $res ? 'success' : 'error';
                    return $res;

                case 'delete': 
                    LoggerUtil::info("ADMIN_MANAGER: [CRUD] Delegando borrado a ContentManager...");
                    $res = $this->contents->remove($postData);
                    LoggerUtil::info("ADMIN_MANAGER: [CRUD] Resultado borrado: " . ($res ? 'ÉXITO' : 'FALLO'));
                    $_GET['status'] = $res ? 'success' : 'error';
                    return $res;

                default: 
                    LoggerUtil::error("ADMIN_MANAGER: [CRUD-ERROR] Acción desconocida: $action");
                    return null;
            }
        } catch (Exception $e) {
            LoggerUtil::error("ADMIN_MANAGER: [CRUD-CRITICAL] Excepción en acción $action: " . $e->getMessage());
            $_GET['status'] = 'error';
            $_GET['message'] = 'Excepción: ' . $e->getMessage();
            return false;
        }
    }

    public function renderFileManager() {
        LoggerUtil::info("ADMIN_MANAGER: [RENDER] Cargando componente Files.php (Media Manager UI)");
        $admin = $this; 
        include __DIR__ . '/../components/Files.php';
    }
}