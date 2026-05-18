<?php
require_once __DIR__ . '/ContentManager.php';
require_once __DIR__ . '/../utils/LoggerUtil.php';
require_once __DIR__ . '/UserManager.php';

function getPageConfigManager($page) {
    global $routeParts;
    $subPage = $routeParts[1] ?? '';

    if (preg_match('/\.(jpg|jpeg|png|gif|ico|css|js|svg)(\?.*)?$/i', $_SERVER['REQUEST_URI'])) {
        if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) {
            if (!(new UserManager())->isAdmin($_SESSION['user_role'] ?? 0)) {
                return ['path' => 'includes/pages/Error.php', 'title' => '403', 'isRoot' => false, 'errorCode' => '403'];
            }
        }
        $fullAssetPath = realpath(__DIR__ . '/../../' . ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));

        if ($fullAssetPath && file_exists($fullAssetPath)) {
            $ext = strtolower(pathinfo($fullAssetPath, PATHINFO_EXTENSION));
            $mimes = [
                'css' => 'text/css', 'js' => 'application/javascript', 'png' => 'image/png',
                'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif',
                'ico' => 'image/x-icon', 'svg' => 'image/svg+xml'
            ];
            header("Content-Type: " . ($mimes[$ext] ?? 'application/octet-stream'));
            readfile($fullAssetPath);
            exit;
        }
        exit;
    }
    $path = ($page === 'admin') 
        ? (empty($subPage) ? 'admin/index.php' : 'admin/includes/pages/' . ucwords($subPage) . '.php') 
        : (in_array($page, ['login', 'register', 'register-confirm']) ? 'includes/pages/AuthView.php' : 'includes/pages/' . ucwords($page) . '.php');

    $fullPath = realpath(__DIR__ . '/../../' . $path);

    if ($fullPath && file_exists($fullPath)) {
        return [
            'path'      => $path,
            'title'     => ($page === 'error') ? "Error " . ($routeParts[1] ?? '404') : ucwords(str_replace(['-', '_'], ' ', $subPage ?: $page)),
            'isRoot'    => in_array($page, ['home', 'admin', '']) && empty($subPage),
            'errorCode' => ($page === 'error') ? ($routeParts[1] ?? '404') : null
        ];
    }

    if (!empty($page) && !in_array($page, ['assets', 'admin', 'includes'])) {

        $categoryData = (new ContentManager())->contentDao->getCategoryBySlug($page);
        if ($categoryData) {
            return [
                'path'   => (count($routeParts) >= 2) ? 'includes/pages/IndividualView.php' : 'includes/pages/main_nav/CategoryView.php',
                'title'  => $categoryData['nombre'],
                'isRoot' => false
            ];
        }
    }

    return ['path' => 'includes/pages/Error.php', 'title' => 'Error 404', 'isRoot' => false, 'errorCode' => '404'];
}