<?php
require_once __DIR__ . '/ContentManager.php';
require_once __DIR__ . '/../utils/LoggerUtil.php';

function getPageConfigManager($page) {
    $baseDir = __DIR__ . '/../../includes/pages/';
    global $routeParts;
    $subPage = $routeParts[1] ?? '';

    if (preg_match('/\.(jpg|jpeg|png|gif|ico|css|js|svg)$/i', $_SERVER['REQUEST_URI'])) {
        return ['path' => 'includes/pages/Error.php', 'title' => '404', 'isRoot' => false, 'errorCode' => '404'];
    }

    if ($page === 'admin') {
        $path = empty($subPage) ? 'admin/index.php' : 'admin/includes/pages/' . ucwords($subPage) . '.php';
    } else {
        $path = in_array($page, ['login', 'register', 'register-confirm']) ? 'includes/pages/AuthView.php' : 'includes/pages/' . ucwords($page) . '.php';
    }

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
        $manager = new ContentManager();
        $categoryData = $manager->contentDao->getCategoryBySlug($page);

        if ($categoryData) {
            return [
                'path'   => (count($routeParts) >= 2) ? 'includes/pages/IndividualView.php' : 'includes/pages/main_nav/CategoryView.php',
                'title'  => $categoryData['nombre'],
                'isRoot' => false
            ];
        }
    }

    return [
        'path'      => 'includes/pages/Error.php',
        'title'     => 'Error 404',
        'isRoot'    => false,
        'errorCode' => '404'
    ];
}