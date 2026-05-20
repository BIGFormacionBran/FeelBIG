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
            case 'edit':
                return $this->contents->saveContent($postData);
            case 'delete':
                return $this->contents->deleteContent($postData['id']);
            default:
                return null;
        }
    }
}