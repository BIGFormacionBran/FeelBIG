<?php
$errorCode = $pageConfig['error_code'] ?? '404';
if(is_numeric($errorCode)) http_response_code($errorCode);
?>

<div class="error-container">
    <div class="error-code-big">
        <?php echo $errorCode; ?>
    </div>

    <h1 class="error-title">Error Detectado</h1>
    
    <p class="error-description">
        La plataforma ha identificado un error <strong><?php echo $errorCode; ?></strong>. 
        La página solicitada no está disponible en este momento.
    </p>

    <div class="error-actions">
        <a href="/home" class="btn-primario">
            Ir al Inicio
        </a>
    </div>
</div>