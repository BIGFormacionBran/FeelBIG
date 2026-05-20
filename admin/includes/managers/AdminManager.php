<?php
require_once __DIR__ . '/ContentManager.php';
require_once __DIR__ . '/MediaManager.php';

class AdminManager {
    public $contents;
    public $media;

    public function __construct() {
        $this->contents = new AdminContentManager();
        $this->media = new MediaManager();
    }

    public function renderFileManager() {
        $file = __DIR__ . '/../components/Files.php';
        if (file_exists($file)) include $file;
    }

    public function handleRequest(array $postData) {
        if (!isset($postData['action'])) return null;

        switch ($postData['action']) {
            case 'add':
                return $this->contents->createContent($postData);
            case 'edit':
                return $this->contents->updateContent($postData['id'], $postData);
            case 'delete':
                return $this->contents->deleteContent($postData['id']);

            case 'fm-upload':
                $type = $postData['type'] ?? 'images';
                $method = ($type === 'videos') ? 'uploadContentVideo' : 'uploadContentImage';
                return $this->media->$method($_FILES['file']);

            case 'fm-delete-file':
                $path = $postData['path'] ?? '';
                if (empty($path)) return false;
                
                // 1. Borrar archivo físico
                $res = FileUtil::delete($path);
                
                // 2. Notificar al DAO para limpiar referencias en DB
                if ($res) {
                    $this->contents->clearReferences($path);
                }
                return $res;

            default:
                return null;
        }
    }
}