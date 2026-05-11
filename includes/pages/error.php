<?php
$errorCode = $pageConfig['error_code'] ?? '404';
if(is_numeric($errorCode)) http_response_code($errorCode);
?>
<div class="error-container">
    <div class="titulo-acceso">Error <?php echo $errorCode; ?></div>
    <a href="/home" class="btn-primario">Volver al Inicio</a>
</div>