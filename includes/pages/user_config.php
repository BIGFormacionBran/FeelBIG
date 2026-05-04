<?php
// Asumimos que la sesión ya está iniciada y tenemos los datos del usuario en $_SESSION
$userData = $_SESSION['usuario'] ?? null;
?>

<div class="container-page">
    <div class="section-header">
        <h1>Configuración de Usuario</h1>
        <p class="subtitle">Gestiona tu información personal y seguridad de la cuenta.</p>
    </div>

    <div class="main-column-padre" style="margin: 30px auto; max-width: 600px; box-shadow: none; border: 1px solid var(--color-border);">
        <div class="login-central-container">
            <form action="includes/actions/update_user.php" method="POST">
                <div class="input-box">
                    <label style="display:block; margin-bottom:5px; font-size:14px; font-weight:600;">Nombre Completo</label>
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($userData['nombre'] ?? ''); ?>" required>
                </div>

                <div class="input-box">
                    <label style="display:block; margin-bottom:5px; font-size:14px; font-weight:600;">Correo Electrónico</label>
                    <input type="email" name="correo" value="<?php echo htmlspecialchars($userData['correo'] ?? ''); ?>" required>
                </div>

                <hr style="width:100%; border:0; border-top:1px solid var(--color-border); margin: 20px 0;">
                <p style="font-size:13px; color:var(--color-texto-muted); margin-bottom:15px;">Deja la contraseña en blanco si no deseas cambiarla.</p>

                <div class="input-box">
                    <label style="display:block; margin-bottom:5px; font-size:14px; font-weight:600;">Nueva Contraseña</label>
                    <input type="password" name="new_pass" id="passInput" placeholder="********">
                    <button type="button" class="btn-ojo" id="toggleBtn">
                        <span class="icon-open">👁️</span>
                    </button>
                </div>

                <button type="submit" class="btn-primario">Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>