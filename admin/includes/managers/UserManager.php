<?php
require_once __DIR__ . '/../daos/AdminUserDao.php';
require_once __DIR__ . '/../../../includes/utils/LoggerUtil.php';

class AdminUserManager {
    private $adminUserDao;

    public function __construct() {
        $this->adminUserDao = new AdminUserDao();
    }

    public function listAllUsers() {
        return $this->adminUserDao->getAllUsers();
    }

    public function save(array $postData) {
        $id = $postData['id'] ?? null;
        $id_tipo = $postData['id_tipo_cuenta'] ?? null;

        if (!$id || !$id_tipo) {
            LoggerUtil::error("ADMIN_USER_MANAGER: Error - Faltan datos para actualizar usuario.");
            return false;
        }

        LoggerUtil::info("ADMIN_USER_MANAGER: Actualizando tipo de cuenta a $id_tipo para usuario ID $id");
        return $this->adminUserDao->updateUserType($id, $id_tipo);
    }

    public function remove(array $postData) {
        $id = $postData['id'] ?? null;
        if (!$id) return false;
        LoggerUtil::info("ADMIN_USER_MANAGER: Eliminando usuario ID $id");
        return $this->adminUserDao->deleteUser($id);
    }
}