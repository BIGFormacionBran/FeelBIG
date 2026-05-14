<?php
$isRegistration = ($page === 'register');
$isConfirmation = ($page === 'register-confirm'); 
$isErrorView = (isset($pageConfig['errorCode']));

$title = $isRegistration ? "Crear cuenta" : ($isConfirmation ? "Verificar código" : "Iniciar sesión");
$btnText = $isRegistration ? "Registrarme" : ($isConfirmation ? "Confirmar" : "Entrar");
$action = $isRegistration ? "/register" : ($isConfirmation ? "/register-confirm" : "/login");

$errorCode = $_GET['error'] ?? null;
$errorMessage = "";

$formData = $_SESSION['formData'] ?? [];
$oldUser = $formData['usuario'] ?? '';
$oldName = $formData['nombre'] ?? '';
$oldPass = $formData['password'] ?? '';

unset($_SESSION['formData']);

if ($errorCode === '1') {
    $errorMessage = $isRegistration ? "El nombre de usuario o correo ya están en uso." : "Usuario o contraseña incorrectos.";
} elseif ($errorCode === 'db') {
    $errorMessage = "Error de conexión con la base de datos.";
} elseif ($errorCode === 'codigo') {
    $errorMessage = "El código de confirmación es incorrecto o ha expirado.";
} elseif ($errorCode === 'mail') {
    $errorMessage = "Error al enviar el correo de verificación. Contacta con soporte.";
}
?>

<script src="https://accounts.google.com/gsi/client" async defer></script>

<div class="auth-wrapper">
    <div class="main-column-padre">
        <?php if ($isErrorView): ?>
            <?php include 'includes/pages/Error.php'; ?>
        <?php else: ?>
            <div class="login-central-container">
                <div class="login-logo">
                    <img src="assets/img/logo.png" alt="Feel BiG">
                </div>

                <div class="titulo-acceso"><?php echo $title; ?></div>

                <?php if ($errorMessage): ?>
                    <div class="error-banner">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>

                <?php if ($isConfirmation): ?>
                    <div id="google-status-msg" style="display:none;">
                        ⚠️ No detectamos sesión activa en el navegador. Revisa tu correo.
                    </div>                
                <?php endif; ?>

                <form action="<?php echo $action; ?>" method="POST">
                    <?php if ($isConfirmation): ?>
                        <p class="muted-text">
                            Introduce el código enviado a:<br>
                            <strong><?php echo htmlspecialchars($_SESSION['temp_email'] ?? ''); ?></strong>
                        </p>
                        <div class="input-box">
                            <input type="text" name="codigo" id="inputCode" placeholder="······" required maxlength="6" autocomplete="one-time-code">
                        </div>
                    <?php else: ?>
                        <?php if ($isRegistration): ?>
                            <div class="input-box">
                                <input type="text" name="nombre" placeholder="Nombre completo" value="<?php echo htmlspecialchars($oldName); ?>" required>
                            </div>
                        <?php endif; ?>

                        <div class="input-box">
                            <input type="text" name="usuario" placeholder="<?php echo $isRegistration ? 'Correo electrónico' : 'Correo o nombre de usuario'; ?>" value="<?php echo htmlspecialchars($oldUser); ?>" required>
                        </div>

                        <div class="input-box">
                            <input type="password" name="password" id="passInput" placeholder="Contraseña" value="<?php echo htmlspecialchars($oldPass); ?>" required minlength="6">
                            <button type="button" class="btn-ojo" id="toggleBtn" aria-label="Mostrar contraseña">                        
                                <svg class="icon-open" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M20.293 2.293a1 1 0 1 1 1.414 1.414l-18 18a1 1 0 0 1-1.414-1.414l3.446-3.446c-.238-.188-.47-.387-.694-.6L1.31 12.722a.985.985 0 0 1 0-1.436l3.734-3.527c3.15-2.976 7.77-3.542 11.48-1.697l3.768-3.768zm-5.275 5.275c-2.852-1.138-6.23-.596-8.582 1.627l-2.974 2.808 2.974 2.809c.233.22.476.423.727.61l1.391-1.39a4 4 0 0 1 5.478-5.478l.986-.986zm-2.5 2.5a2.001 2.001 0 0 0-2.45 2.45l2.45-2.45z"></path>
                                    <path d="M22.69 11.285 19.7 8.463l-1.414 1.414 2.251 2.126-2.973 2.809a8.099 8.099 0 0 1-6.377 2.164l-1.712 1.712c3.268.833 6.876.02 9.48-2.44l3.733-3.527a.985.985 0 0 0 0-1.436z"></path>
                                    <path d="M15.997 12.167a4 4 0 0 1-3.83 3.83l3.83-3.83z"></path>
                                </svg>
                                <svg class="icon-closed" viewBox="0 0 24 24" fill="currentColor" style="display:none;">
                                    <path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 12c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn-primario"><?php echo $btnText; ?></button>

                    <div class="auth-footer-links">
                        <?php if ($isConfirmation): ?>
                            <p class="muted-text">¿No has recibido ningún correo?</p>
                            <a href="/resend-code" class="enlace-personalizado enlace-bold">Reenviar código de confirmación</a>
                            <div>
                                <a href="/register" class="enlace-personalizado enlace-muted">Volver al registro</a>
                            </div>
                        <?php else: ?>
                            <a href="/<?php echo $isRegistration ? 'login' : 'register'; ?>" class="enlace-personalizado enlace-muted">
                                <?php echo $isRegistration ? '¿Ya tienes cuenta? Inicia sesión' : '¿No tienes cuenta? Regístrate'; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <?php if(function_exists('renderSignatureUtil')) echo renderSignatureUtil(); ?>
        <?php endif; ?>
    </div>
</div>