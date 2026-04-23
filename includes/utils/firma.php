<?php
function renderInfoFooter() {
    $fecha = date('d/m/Y');
    $empresa = "Academia trinidad S.L.";
    
    return "
        <div class='info-container-global'>
            <p><strong>Firma:</strong> $empresa</p>
            <p><strong>Fecha:</strong> $fecha</p>
        </div>
    ";
}
?>