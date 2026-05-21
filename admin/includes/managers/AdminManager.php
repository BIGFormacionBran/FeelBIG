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

    public function renderFileManager() {
        $file = __DIR__ . '/../components/Files.php';
        if (file_exists($file)) {
            $admin = $this; 
            include $file;
        }
    }

    public function handleRequest(array $postData) {
        $action = $postData['action'] ?? null;
        if (!$action) return null;

        switch ($action) {
            case 'add':    return $this->contents->add($postData);
            case 'edit':   return $this->contents->save($postData);
            case 'delete': return $this->contents->remove($postData);
            
            case 'fm-upload':
                $type = $postData['type'] ?? 'images';
                $method = ($type === 'videos') ? 'uploadContentVideo' : 'uploadContentImage';
                return $this->media->$method($_FILES['file']);

            case 'fm-delete-file':
                $path = $postData['path'] ?? '';
                if (empty($path)) return false;
                $res = FileUtil::delete($path);
                if ($res) $this->contents->clearReferences($path);
                return $res;

            default: return null;
        }
    }
}