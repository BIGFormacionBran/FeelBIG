<?php
/**
 * Componente de Alertas reutilizable
 * @var string $status  Puede ser "success", "error" o vacío
 * @var string $message Mensaje personalizado para errores
 */
?>

<?php if ($status === "success"): ?>
    <div class="admin-status-alert success">Operación realizada con éxito.</div>
<?php elseif ($status === "error"): ?>
    <div class="admin-status-alert error"><?php echo htmlspecialchars($message ?: "Error en la operación."); ?></div>
<?php endif; ?>