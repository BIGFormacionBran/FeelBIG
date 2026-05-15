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
        LoggerUtil::info("CRUMB_LOG: Input -> Page: [$currentPage] | RouteParts: " . json_encode($routeParts));
        if (in_array($currentPage, ['home', 'login', 'register', 'configuracion', 'error'])) return null;
        
        if ($currentPage === 'admin' && isset($routeParts[1])) {
            $breadcrumbs = [['title' => 'Admin', 'link' => '/admin']];
            $breadcrumbs[] = ['title' => ucwords(str_replace(['-', '_'], ' ', urldecode($routeParts[1]))), 'link' => null];
        } else {
            $breadcrumbs = [['title' => 'Home', 'link' => '/home']];
        }
        
        $currentSlug = ($currentPage === 'IndividualView') ? ($routeParts[0] ?? null) : $currentPage;
        LoggerUtil::info("CRUMB_LOG: Calculated Slug to search: [$currentSlug]");
        if ($currentSlug) {
            $categoryData = $this->contentDao->getCategoryBySlug($currentSlug);
            if ($categoryData) {
                LoggerUtil::info("CRUMB_LOG: Category found: " . $categoryData['nombre']);
                if (!empty($categoryData['id_padre'])) {
                    $parent = $this->contentDao->getCategoryById($categoryData['id_padre']);
                    if ($parent) {
                        LoggerUtil::info("CRUMB_LOG: Parent category found: " . $parent['nombre']);
                        $breadcrumbs[] = ['title' => $parent['nombre'], 'link' => '/' . strtolower(str_replace(' ', '-', $parent['nombre']))];
                    }
                }
                $isIndividual = ($currentPage === 'IndividualView');
                LoggerUtil::info("CRUMB_LOG: Is Individual? " . ($isIndividual ? 'YES' : 'NO'));
                $breadcrumbs[] = ['title' => $categoryData['nombre'], 'link' => $isIndividual ? '/' . $currentSlug : null];
                
                if ($isIndividual) {
                    $itemName = isset($routeParts[1]) ? ucwords(str_replace(['-', '_'], ' ', urldecode($routeParts[1]))) : 'Desconocido';
                    $breadcrumbs[] = ['title' => $itemName, 'link' => null];
                    LoggerUtil::info("CRUMB_LOG: Adding individual item: [$itemName]");
                }
            }
        }
        LoggerUtil::info("CRUMB_LOG: Final Breadcrumbs count: " . count($breadcrumbs));
        return $breadcrumbs;
    }
}