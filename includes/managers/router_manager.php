<?php
function get_page_config_manager($page) {
    $folders = ['includes/pages/main_nav/', 'includes/pages/'];
    foreach ($folders as $folder) {
        $filePath = $folder . $page . '.php';
        if (file_exists($filePath)) {
            return [
                'path' => $filePath,
                'title' => ucwords(str_replace('-', ' ', $page)),
                'is_root' => ($page === 'home')
            ];
        }
    }
    return ['path' => 'includes/pages/home.php', 'title' => 'Home', 'is_root' => true];
}