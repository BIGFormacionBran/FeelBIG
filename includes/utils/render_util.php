<?php
function render_signature_util() {
    $fecha = date('d/m/Y');
    $empresa = "Academia trinidad S.L.";
    return "
        <div class='info-container-global'>
            <p><strong>Firma:</strong> $empresa</p>
            <p><strong>Fecha:</strong> $fecha</p>
        </div>";
}

function render_auto_components_util($folder) {
    $path = __DIR__ . '/../../' . $folder;
    if (!is_dir($path)) return;
    $components = glob($path . "/*.php");
    sort($components);
    foreach ($components as $component) {
        include $component;
    }
}