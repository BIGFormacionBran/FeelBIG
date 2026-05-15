<?php
require_once __DIR__ . '/ContentManager.php';

function getPageConfigManager($page) {
    $baseDir = __DIR__ . '/../../includes/pages/';
    global $routeParts;

    if ($page === 'admin') {
        $path = empty($subPage) ? 'admin/index.php' : 'admin/includes/pages/' . ucwords($subPage) . '.php';
    } else {
        if (in_array($page, ['login', 'register', 'register-confirm'])) {
            $path = 'includes/pages/AuthView.php';
        } else {
            $path = 'includes/pages/' . ucwords($page) . '.php';
        }
    }

    if (file_exists(__DIR__ . '/../../' . $path)) {
        $subPage = $routeParts[1] ?? '';
        return [
            'path'      => $path,
            'title'     => ($page === 'error') ? "Error " . ($routeParts[1] ?? '404') : ucwords(str_replace(['-', '_'], ' ', $subPage ?: $page)),
            'isRoot'    => in_array($page, ['home', 'admin', '']) && empty($subPage),
            'errorCode' => ($page === 'error') ? ($routeParts[1] ?? '404') : null
        ];
    }

    // 2. AUTOMATIC DATABASE SEARCH (Categories)
    $manager = new ContentManager();
    $categoryData = $manager->contentDao->getCategoryBySlug($page);
    if ($categoryData) {
        return [
            'path'   => (count($routeParts) >= 2) ? 'includes/pages/IndividualView.php' : 'includes/pages/main_nav/CategoryView.php',
            'title'  => $categoryData['nombre'],
            'isRoot' => false
        ];
    }

    return [
        'path'      => 'includes/pages/Error.php',
        'title'     => 'Error 404',
        'isRoot'    => false,
        'errorCode' => '404'
    ];
}