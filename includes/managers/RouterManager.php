<?php
require_once __DIR__ . '/ContentManager.php';

function getPageConfigManager($page) {
    $baseDir = __DIR__ . '/../../includes/pages/';
    global $routeParts;

    if ($page === 'error') {
        $errorCode = $GLOBALS['routeParts'][1] ?? '404';
        return [
            'path'      => 'includes/pages/Error.php',
            'title'     => 'Error ' . $errorCode,
            'isRoot'    => false,
            'errorCode' => $errorCode
        ];
    }

    // 1. AUTOMATIC FILE SCAN    
    $fileToLoad = null;
    if (file_exists($baseDir . $page . '.php')) {
        $fileToLoad = 'includes/pages/' . $page . '.php';
    } elseif (in_array($page, ['login', 'register', 'register-confirm'])) {
        $fileToLoad = 'includes/pages/AuthView.php';
    } elseif ($page === 'home' || empty($page)) {
        $fileToLoad = 'includes/pages/Home.php';
    }

    if ($fileToLoad) {
        return [
            'path'   => $fileToLoad,
            'title'  => ucwords(str_replace(['-', '_'], ' ', $page)),
            'isRoot' => ($page === 'home' || empty($page))
        ];
    }

    // 2. AUTOMATIC DATABASE SEARCH (Categories)
    $manager = new ContentManager();
    $categoryData = $manager->contentDao->getCategoryBySlug($page);
    if ($categoryData) {
        if (count($routeParts) >= 2) {
            return [
                'path'   => 'includes/pages/IndividualView.php',
                'title'  => ucwords(str_replace('-', ' ', urldecode($routeParts[1]))),
                'isRoot' => false
            ];
        }
        return [
            'path'   => 'includes/pages/main_nav/CategoryView.php',
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