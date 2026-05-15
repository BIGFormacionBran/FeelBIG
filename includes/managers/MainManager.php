<?php
require_once __DIR__ . '/../daos/ContentDao.php';
require_once __DIR__ . '/UserManager.php';
require_once __DIR__ . '/../utils/LoggerUtil.php';

class MainManager {
    private $contentDao;
    public $userManager;

    public function __construct() {
        LoggerUtil::info("MainManager: Initializing main manager.");
        $this->contentDao = new ContentDao();
        $this->userManager = new UserManager();
    }

    public function startRegistration($name, $email, $pass) { return $this->userManager->startRegistration($name, $email, $pass); }
    public function confirmRegistration($email, $code) { return $this->userManager->confirmRegistration($email, $code); }
    public function login($email, $pass) { return $this->userManager->login($email, $pass); }
    public function getUserById($id) { return $this->userManager->getUserById($id); }
    public function updateProfile($id, $name, $email, $pass) { return $this->userManager->updateProfile($id, $name, $email, $pass); }

    public function getBreadcrumbs($currentPage, $routeParts) {
        LoggerUtil::info("MainManager: Generating breadcrumbs for $currentPage");
        if (in_array($currentPage, ['home', 'login', 'register', 'configuracion', 'error'])) return null;
        
        if ($currentPage === 'admin' && isset($routeParts[1])) {
            $breadcrumbs = [['title' => 'Admin', 'link' => '/admin']];
            $breadcrumbs[] = ['title' => ucwords(str_replace(['-', '_'], ' ', urldecode($routeParts[1]))), 'link' => null];
        }
            
        $breadcrumbs = [['title' => 'Home', 'link' => '/home']];
        $currentSlug = ($currentPage === 'IndividualView') ? ($routeParts[0] ?? null) : $currentPage;
        if ($currentSlug) {
            $categoryData = $this->contentDao->getCategoryBySlug($currentSlug);
            if ($categoryData) {
                if (!empty($categoryData['id_padre'])) {
                    $parent = $this->contentDao->getCategoryById($categoryData['id_padre']);
                    if ($parent) {
                        $breadcrumbs[] = ['title' => $parent['nombre'], 'link' => '/' . strtolower(str_replace(' ', '-', $parent['nombre']))];
                    }
                }
                $isIndividual = ($currentPage === 'IndividualView');
                $breadcrumbs[] = ['title' => $categoryData['nombre'], 'link' => $isIndividual ? '/' . $currentSlug : null];
                
                if ($isIndividual) {
                    $breadcrumbs[] = ['title' => ucwords(str_replace(['-', '_'], ' ', urldecode($routeParts[1]))), 'link' => null];
                }
            }
        }
        return $breadcrumbs;
    }
}