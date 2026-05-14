<?php
$errorCode = $pageConfig['errorCode'] ?? '404';

$errorDestination = isset($_SESSION['user_id']) ? '/home' : '/login';
$buttonText = isset($_SESSION['user_id']) ? 'Volver al Inicio' : 'Ir al Login';
?>
<div class="error-container">
    <div class="error-header">
        <div class="titulo-error">Error <?php echo $errorCode; ?></div>
    </div>
    <div class="error-footer">
        <a href="<?php echo $errorDestination; ?>" class="btn-primario"><?php echo $buttonText; ?></a>
    </div>    
</div>