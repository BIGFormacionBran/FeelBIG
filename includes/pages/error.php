<?php
$errorCode = $pageConfig['error_code'] ?? '404';
if(is_numeric($errorCode)) http_response_code($errorCode);
?>

<div class="auth-wrapper">
    <div class="main-column-padre">
        <div class="login-central-container error-page-variant">
            
            <div class="error-visual-header">
                <div class="error-icon-circle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <span class="error-number-badge"><?php echo $errorCode; ?></span>
            </div>

            <div class="titulo-acceso">Error <?php echo $errorCode; ?></div>

            <p class="error-description-text">
                La plataforma ha detectado un problema con la solicitud actual. 
                Por favor, regresa al panel principal para continuar.
            </p>

            <div class="error-actions-footer">
                <a href="/home" class="btn-primario">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Volver al Inicio
                </a>
            </div>
        </div>
    </div>
</div>