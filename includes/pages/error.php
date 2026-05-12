<?php
$errorCode = $pageConfig['error_code'] ?? '404';

$destinoError = isset($_SESSION['user_id']) ? '/home' : '/login';
$textoBoton = isset($_SESSION['user_id']) ? 'Volver al Inicio' : 'Ir al Login';
?>
<div class="error-container">
    <div class="error-header">
        <div class="titulo-error">Error <?php echo $errorCode; ?></div>
    </div>
    <div class="error-footer">
        <a href="<?php echo $destinoError; ?>" class="btn-primario"><?php echo $textoBoton; ?></a>
    </div>    
</div>