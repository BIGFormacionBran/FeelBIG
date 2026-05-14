<?php
function renderSignatureUtil() {
    $date = date('d/m/Y');
    $company = "Academia trinidad S.L.";
    return "
        <div class='info-container-global'>
            <p><strong>Firma:</strong> $company</p>
            <p><strong>Fecha:</strong> $date</p>
        </div>";
}

function renderAutoComponentsUtil($folder) {
    $path = __DIR__ . '/../../' . $folder;
    if (!is_dir($path)) return;
    $components = glob($path . "/*.php");
    sort($components);
    foreach ($components as $component) {
        include $component;
    }
}

function renderPageLayoutManager($page, $pageConfig, $authPages) {
    $isErrorPage = (isset($pageConfig['errorCode']) || strpos($pageConfig['path'], 'Error.php') !== false);
    $isLoggedIn = isset($_SESSION['user_id']);
    
    if (in_array($page, $authPages) || ($isErrorPage && !$isLoggedIn)) {
        include 'includes/pages/AuthView.php';
    } else {
        include 'includes/components/Header.php';
        echo '<div class="main-content-wrapper">';
            if (!$pageConfig['isRoot'] && !$isErrorPage) {
                include 'includes/components/generic/Breadcrumbs.php';
            }
            include $pageConfig['path'];
        echo '</div>';
        include 'includes/components/Footer.php';
    }
}