<?php
$errorCode = $pageConfig['error_code'] ?? '404';
if(is_numeric($errorCode)) http_response_code($errorCode);
?>
<div class="error-container">
    <div class="error-header">
        <div class="titulo-error">Error <?php echo $errorCode; ?></div>
    </div>
    <div class="error-footer">
        <a href="/home" class="btn-primario">Volver al Inicio</a>
    </div>    
</div>