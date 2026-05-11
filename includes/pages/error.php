<?php
$errorCode = $pageConfig['error_code'] ?? '404';
if(is_numeric($errorCode)) http_response_code($errorCode);
?>

<div class="container-page">
    <div class="main-column-padre" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 80px 20px; text-align: center; box-shadow: none; background: transparent;">
        
        <div style="font-size: 150px; font-weight: 900; color: var(--color-principal-soft); line-height: 1; user-select: none;">
            <?php echo $errorCode; ?>
        </div>

        <h1 style="font-size: 24px; color: var(--color-texto); margin-top: -20px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 2px;">
            Error detectado
        </h1>
        
        <p style="color: var(--color-texto-muted); font-size: 16px; max-width: 400px; margin-bottom: 40px; line-height: 1.6;">
            La plataforma ha identificado un error <strong><?php echo $errorCode; ?></strong>. 
            La página solicitada no está disponible en este momento.
        </p>

        <div style="display: flex; gap: 15px; width: 100%; max-width: 300px;">
            <a href="/home" class="btn-primario" style="text-decoration: none; margin: 0;">
                Ir al Inicio
            </a>
            <button onclick="window.history.back()" class="enlace-personalizado" style="background:none; border:none; cursor:pointer; width: 100%;">
                Volver atrás
            </button>
        </div>

        <?php if(function_exists('render_signature_util')) : ?>
            <div style="margin-top: 60px; width: 100%;">
                <?php echo render_signature_util(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>