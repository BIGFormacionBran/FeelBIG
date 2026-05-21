<?php
$status = $status ?? $_GET['status'] ?? null;
$message = $message ?? $_GET['message'] ?? null;
?>

<?php if ($status === "success"): ?>
    <div class="admin-status-alert success">Operación realizada con éxito.</div>
<?php elseif ($status === "error"): ?>
    <div class="admin-status-alert error"><?php echo htmlspecialchars($message ?: "Error en la operación."); ?></div>
<?php endif; ?>