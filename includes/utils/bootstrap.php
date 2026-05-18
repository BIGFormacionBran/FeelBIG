<?php
session_start();
$basePath = dirname(__DIR__, 2);
$logFile = $basePath . '/logs/php_error.log';

if (!is_dir($basePath . '/logs')) {
    mkdir($basePath . '/logs', 0755, true);
}

ini_set('display_errors', 0); 
ini_set('log_errors', 1);      
ini_set('error_log', $logFile); 
error_reporting(E_ALL);

// 1. Load UTILS layer
require_once $basePath . '/includes/utils/AssetsUtil.php';
require_once $basePath . '/includes/utils/RenderUtil.php';
require_once $basePath . '/includes/utils/IndividualRenderUtil.php';
require_once $basePath . '/includes/utils/DbUtil.php';
require_once $basePath . '/includes/utils/CardRenderUtil.php';

// 2. Load MANAGERS layer
require_once $basePath . '/includes/managers/RouterManager.php';
require_once $basePath . '/includes/managers/MainManager.php';
require_once $basePath . '/includes/managers/UserManager.php';

// 3. Routing Logic
$rawRoute = $_GET['route'] ?? 'home';
if (isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS'] >= 400) {
    $cleanRoute = 'error/' . $_SERVER['REDIRECT_STATUS'];
} else {
    $cleanRoute = trim($rawRoute, '/');
}

global $routeParts;
$routeParts = explode('/', $cleanRoute);
$page = (empty($routeParts[0])) ? 'home' : $routeParts[0];

// --- ADMIN PROTECTION ---
if ($page === 'admin') {
    $userManager = new UserManager();

    $userRole = $_SESSION['user_role'] ?? 0;

    if (!isset($_SESSION['user_id']) || !$userManager->isAdmin($userRole)) {
        LoggerUtil::error("Intento de acceso no autorizado a /admin. ID: " . ($_SESSION['user_id'] ?? 'Anónimo') . " | Rol: $userRole");
        header("Location: /error/403");
        exit();
    }
}

// Resend code logic
if ($cleanRoute === 'resend-code') {
    $email = $_SESSION['temp_email'] ?? '';
    $name = $_SESSION['temp_name'] ?? 'Usuario';
    if (!empty($email)) {
        $manager = new MainManager();
        if ($manager->startRegistration($name, $email, "RESEND_ONLY")) {
            header("Location: /register-confirm?sent=1");
            exit();
        }
    }
    header("Location: /register?error=mail");
    exit();
}

// --- POST PROCESSING DETECTION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($cleanRoute === 'login') { require_once $basePath . '/Auth.php'; exit(); }
    if ($cleanRoute === 'register') { require_once $basePath . '/ProcessRegistration.php'; exit(); }
    if ($cleanRoute === 'register-confirm') { require_once $basePath . '/ProcessConfirmation.php'; exit(); }
}

$authPages = ['login', 'register', 'register-confirm'];
$pageConfig = getPageConfigManager($page);
$isError = isset($pageConfig['errorCode']);

if ($isError && is_numeric($pageConfig['errorCode'])) {
    http_response_code($pageConfig['errorCode']);
}

// 4. Session Logic
if (!isset($_SESSION['user_id']) && !in_array($page, $authPages) && !$isError) {
    header("Location: /login");
    exit();
}

// 5. Page Config Load
$mainCss = getMinifiedCssUtil();
$adminCss = getMinifiedCssUtil('admin', 'admin/assets/css');
$needsSwiper = false;