<?php
require_once __DIR__ . '/../daos/UserDao.php';
require_once __DIR__ . '/../daos/PendingRegistrationDao.php';
require_once __DIR__ . '/MailManager.php';

class UserManager {
    private $userDao;
    private $pendingRegistrationDao;
    private $mailManager;

    public function __construct() {
        $this->userDao = new UserDao();
        $this->pendingRegistrationDao = new PendingRegistrationDao();
        $this->mailManager = new MailManager();
    }

    public function startRegistration($name, $email, $pass) {
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $dbResult = $this->pendingRegistrationDao->createTemporal($name, $email, $pass, $code);
        if (!$dbResult) return false;
        
        $mailResult = $this->mailManager->sendRegistrationConfirmation($email, $name, $code);
        return $mailResult;
    }

    public function confirmRegistration($email, $code) {
        $data = $this->pendingRegistrationDao->getAndValidate($email, $code);
        if ($data) {
            if ($this->userDao->registerWithHash($data['nombre'], $data['correo'], $data['password'])) {
                $this->pendingRegistrationDao->deleteTemporal($email);
                return true;
            }
        }
        return false;
    }

    public function login($identifier, $pass) {
        return $this->userDao->login($identifier, $pass);
    }

    public function getUserById($id) {
        return $this->userDao->getById($id);
    }

    public function updateProfile($id, $name, $email, $password) {
        $user = $this->getUserById($id);
        if (!$user) return "Error: Usuario no encontrado.";
        
        $finalName = !empty($name) ? $name : $user['nombre'];
        $finalEmail = !empty($email) ? $email : $user['correo'];
        $finalPass = !empty($password) ? $password : null;

        return $this->userDao->updateProfile($id, $finalName, $finalEmail, $finalPass) 
               ? "Perfil actualizado correctamente." : "Error al actualizar.";
    }

    public function isAdmin($roleId) {
        return in_array((int)$roleId, [1, 2]); 
    }
}