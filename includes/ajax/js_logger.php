<?php
require_once __DIR__ . '/../utils/logger_util.php';

// Solo aceptamos POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['message'])) {
        $level = $data['level'] ?? 'ERROR';
        $msg = "[JS_CLIENT] " . $data['message'];
        
        Logger::log($msg, $level);
        echo json_encode(['status' => 'logged']);
    }
}