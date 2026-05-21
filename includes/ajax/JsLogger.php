<?php
require_once __DIR__ . '/../utils/LoggerUtil.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['message'])) {
        $level = $data['level'] ?? 'ERROR';
        $message = "[JS_CLIENT] " . $data['message'];
        
        LoggerUtil::error($message);
        echo json_encode(['status' => 'logged']);
    }
}