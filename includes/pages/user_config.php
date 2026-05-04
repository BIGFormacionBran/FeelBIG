<?php
require_once 'includes/managers/main_manager.php';
$manager = new MainManager();
// Obtenemos los datos completos del usuario desde la DB usando el ID de la sesión
$userData = $manager->get_user_by_id($_SESSION['user_id']);
?>

<div class="container-page">
    <div class="section-header">
        <h1>Configuración de Usuario</h1>
        <p class="subtitle">Gestiona tu información personal y seguridad de la cuenta.</p>
    </div>

    <div class="main-column-padre">
        <div class="info-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <span>Los campos vacíos mantendrán su valor actual. Deja la contraseña en blanco si no deseas cambiarla.</span>
        </div>

        <div class="login-central-container">
            <form action="includes/actions/update_user.php" method="POST">
                
                <div class="input-box">
                    <label for="nombreInput">Nombre Completo</label>
                    <input type="text" name="nombre" id="nombreInput" placeholder="<?php echo htmlspecialchars($userData['nombre'] ?? ''); ?>">
                </div>

                <div class="input-box">
                    <label for="correoInput">Correo Electrónico</label>
                    <input type="email" name="correo" id="correoInput" placeholder="<?php echo htmlspecialchars($userData['correo'] ?? ''); ?>">
                </div>

                <hr class="separator">

                <div class="input-box">
                    <label for="passInput">Nueva Contraseña</label>
                    <input type="password" name="new_pass" id="passInput" placeholder="********">
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

                <button type="submit" class="btn-primario">Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>