<?php
$is_registro = ($page === 'register');
$is_confirmacion = ($page === 'register-confirm'); 

$titulo = $is_registro ? "Crear cuenta" : ($is_confirmacion ? "Verificar código" : "Iniciar sesión");
$btn_text = $is_registro ? "Registrarme" : ($is_confirmacion ? "Confirmar" : "Entrar");
$action = $is_registro ? "/register" : ($is_confirmacion ? "/register-confirm" : "/login");

$error_code = $_GET['error'] ?? null;
$mensaje_error = "";

$form_data = $_SESSION['form_data'] ?? [];
$old_user = $form_data['usuario'] ?? '';
$old_nombre = $form_data['nombre'] ?? '';
$old_pass = $form_data['password'] ?? '';

unset($_SESSION['form_data']);

if ($error_code === '1') {
    $mensaje_error = $is_registro ? "El nombre de usuario o correo ya están en uso." : "Usuario o contraseña incorrectos.";
} elseif ($error_code === 'db') {
    $mensaje_error = "Error de conexión con la base de datos.";
} elseif ($error_code === 'codigo') {
    $mensaje_error = "El código de confirmación es incorrecto o ha expirado.";
} elseif ($error_code === 'mail') {
    $mensaje_error = "Error al enviar el correo de verificación. Contacta con soporte.";
}
?>

<script src="https://accounts.google.com/gsi/client" async defer></script>

<div class="auth-wrapper">
    <div class="main-column-padre">
        <div class="login-central-container">
            <div class="login-logo">
                <img src="assets/img/logo.png" alt="Feel BiG">
            </div>

            <div class="titulo-acceso"><?php echo $titulo; ?></div>

            <?php if ($mensaje_error): ?>
                <div class="error-banner">
                    <?php echo $mensaje_error; ?>
                </div>
            <?php endif; ?>

            <div id="google-status-msg" style="display:none; color: #856404; background: #fff3cd; padding: 10px; border-radius: 5px; font-size: 12px; margin-bottom: 15px; text-align: center; border: 1px solid #ffeeba;">
                ⚠️ No detectamos sesión activa en el navegador. Revisa tu correo.
            </div>

            <form action="<?php echo $action; ?>" method="POST">
                <?php if ($is_confirmacion): ?>
                    <p style="color: var(--color-texto-muted); text-align: center; margin-bottom: 25px; font-size: 14px;">
                        Introduce el código enviado a:<br>
                        <strong style="color: var(--color-texto);"><?php echo htmlspecialchars($_SESSION['temp_email'] ?? ''); ?></strong>
                    </p>
                    <div class="input-box">
                        <input type="text" name="codigo" id="inputCodigo" placeholder="······" required 
                               maxlength="6" autocomplete="one-time-code"
                               style="text-align:center; letter-spacing: 12px; font-size: 24px; font-weight: 800; color: var(--color-principal);">
                    </div>
                <?php else: ?>
                    <?php if ($is_registro): ?>
                    <div class="input-box">
                        <input type="text" name="nombre" placeholder="Nombre completo" value="<?php echo htmlspecialchars($old_nombre); ?>" required>
                    </div>
                    <?php endif; ?>

                    <div class="input-box">
                        <input type="text" name="usuario" placeholder="<?php echo $is_registro ? 'Correo electrónico' : 'Correo o nombre de usuario'; ?>" value="<?php echo htmlspecialchars($old_user); ?>" required>
                    </div>

                    <div class="input-box">
                        <input type="password" name="password" id="passInput" placeholder="Contraseña" value="<?php echo htmlspecialchars($old_pass); ?>" required minlength="6">
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

                <button type="submit" class="btn-primario"><?php echo $btn_text; ?></button>

                <div class="auth-footer-links">
                    <?php if (!$is_confirmacion): ?>
                        <a href="/<?php echo $is_registro ? 'login' : 'register'; ?>" class="enlace-personalizado">
                            <?php echo $is_registro ? '¿Ya tienes cuenta? Inicia sesión' : '¿No tienes cuenta? Regístrate'; ?>
                        </a>
                    <?php else: ?>
                        <p style="font-size: 13px; color: var(--color-texto-muted); margin-top: 15px;">¿No has recibido ningún correo?</p>
                        <a href="/resend-code" class="enlace-personalizado" style="font-weight: 700;">Reenviar código de confirmación</a>
                        <div style="margin-top: 15px;">
                            <a href="/register" class="enlace-personalizado" style="font-size: 12px; opacity: 0.7;">Volver al registro</a>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <?php if(function_exists('render_signature_util')) echo render_signature_util(); ?>
    </div>
</div>