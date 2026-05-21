<?php
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

$status = $status ?? $_GET['status'] ?? null;
$message = $message ?? $_GET['message'] ?? null;

if ($status) {
    LoggerUtil::info("ALERTS_RENDER: Mostrando alerta. Status: [$status], Message: [" . ($message ?? 'N/A') . "]");
}
?>

<?php if ($status === "success"): ?>
    <div class="admin-status-alert success">Operación realizada con éxito.</div>
<?php elseif ($status === "error"): ?>
    <div class="admin-status-alert error"><?php echo htmlspecialchars($message ?: "Error en la operación."); ?></div>
<?php endif; ?>