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
                    <button type="button" class="btn-ojo" id="toggleBtn"></button>
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