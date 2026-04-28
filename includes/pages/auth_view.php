<?php
$is_registro = (isset($_GET['page']) && $_GET['page'] === 'registro');
$titulo = $is_registro ? "Crear cuenta" : "Iniciar sesión";
$btn_text = $is_registro ? "Registrarme" : "Entrar";
$action = $is_registro ? "process_registro.php" : "auth.php";

$error_code = $_GET['error'] ?? null;
$mensaje_error = "";

if ($error_code === '1') {
    $mensaje_error = $is_registro ? "El correo ya está registrado o hay un error de datos." : "Usuario o contraseña incorrectos.";
} elseif ($error_code === 'db') {
    $mensaje_error = "Error de conexión con la base de datos.";
}
?>

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

            <form action="<?php echo $action; ?>" method="POST">
                <?php if ($is_registro): ?>
                <div class="input-box">
                    <input type="text" name="nombre" placeholder="Nombre completo" required>
                </div>
                <?php endif; ?>

                <div class="input-box">
                    <input type="email" name="usuario" placeholder="Correo electrónico" required>
                </div>

                <div class="input-box">
                    <input type="password" name="password" id="passInput" placeholder="Contraseña" required minlength="6">
                    <button type="button" class="btn-ojo" id="toggleBtn" aria-label="Mostrar contraseña">
                        <svg class="icon-open" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 12c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                        <svg class="icon-closed" viewBox="0 0 24 24" fill="currentColor" style="display:none;">
                            <path d="M2 3.41L3.41 2l18.59 18.59L20.59 22l-2.22-2.22A11.12 11.12 0 0112 21c-7 0-10-7-10-7a13.3 13.3 0 012.35-3.41L2 3.41zM12 17c2.76 0 5-2.24 5-5a4.91 4.91 0 00-.24-1.52L13.52 13.72A3 3 0 019.28 9.48l-1.84-1.84A4.91 4.91 0 007 12c0 2.76 2.24 5 5 5zm8.56-4.32A13.32 13.32 0 0022 12s-3-7-10-7a11.11 11.11 0 00-4.08.77l1.52 1.52A5.05 5.05 0 0112 7c2.76 0 5 2.24 5 5 0 .61-.11 1.19-.31 1.72l1.87 1.96z"/>
                        </svg>
                    </button>
                </div>

                <button type="submit" class="btn-primario"><?php echo $btn_text; ?></button>

                <div class="auth-footer-links">
                    <a href="index.php?page=<?php echo $is_registro ? 'login' : 'registro'; ?>" class="enlace-personalizado">
                        <?php echo $is_registro ? '¿Ya tienes cuenta? Inicia sesión' : '¿No tienes cuenta? Regístrate'; ?>
                    </a>
                </div>
            </form>
        </div>
        <?php if(function_exists('render_signature_util')) echo render_signature_util(); ?>
    </div>
</div>