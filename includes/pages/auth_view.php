<?php
// Detectamos el modo según la URL
$is_registro = (isset($_GET['page']) && $_GET['page'] === 'registro');
$titulo = $is_registro ? "Crear cuenta" : "Iniciar sesión";
$btn_text = $is_registro ? "Registrarme" : "Entrar";
$action = $is_registro ? "process_registro.php" : "auth.php";
?>

<div class="auth-wrapper">
    <div class="main-column-padre">
        <div class="login-central-container">
            <div class="login-logo">
                <img src="assets/img/logo.png" alt="Feel BiG" style="width: 160px; margin-bottom: 20px;">
            </div>

            <div class="titulo-acceso"><?php echo $titulo; ?></div>

            <form action="<?php echo $action; ?>" method="POST" style="width: 100%;">
                
                <?php if ($is_registro): ?>
                <div class="input-box">
                    <input type="text" name="nombre" placeholder="Nombre completo" required>
                </div>
                <?php endif; ?>

                <div class="input-box">
                    <input type="text" name="usuario" placeholder="Correo electrónico o usuario" required>
                </div>

                <div class="input-box">
                    <input type="password" name="password" id="passInput" placeholder="Contraseña" required>
                    <button type="button" class="btn-ojo" id="toggleBtn" title="Mostrar/Ocultar">
                        <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 19c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>

                <button type="submit" class="btn-primario"><?php echo $btn_text; ?></button>

                <div style="margin-top: 25px; text-align: center;">
                    <?php if ($is_registro): ?>
                        <a href="index.php?page=login" class="enlace-personalizado">¿Ya tienes cuenta? Inicia sesión</a>
                    <?php else: ?>
                        <a href="index.php?page=registro" class="enlace-personalizado">¿No tienes cuenta? Regístrate</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <?php if(function_exists('renderInfoFooter')) echo renderInfoFooter(); ?>
    </div>
</div>