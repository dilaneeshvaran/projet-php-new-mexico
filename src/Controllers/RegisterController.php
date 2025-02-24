<?php

namespace App\Controllers;

use App\Services\RegisterService;
use App\Repositories\UserRepository;
use App\Repositories\PageRepository;
use App\Core\SQL;
use App\Core\View;
use App\Core\Session;

class RegisterController
{
    private UserRepository $userRepository;
    private PageRepository $pageRepository;
    private RegisterService $registerService;
    public function __construct()
    {
        $db = new SQL();
        $this->registerService = new RegisterService(new UserRepository($db));
        $this->pageRepository = new PageRepository($db);
    }

    public function index(): void
    {
        $session = new Session();
        if ($session->isLogged()) {
            header('Location: /');
            exit();
        }
        $this->renderRegisterPage();
        
    }

    public function post(): void
    {
        $errors = [];
        $formData = $_POST;

        try {
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
                $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                header('Location: /register');
                exit();
            }

            $errors = $this->registerService->registerUser($_POST);

            if (empty($errors)) {
                header('Location: /register/success');
                exit();
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        $this->renderRegisterPage($errors, $formData);
    }

    private function renderRegisterPage(array $errors = [], array $formData = []): void
    {
        $pageId = 1;
        $pageData = $this->pageRepository->findOneById($pageId);
        /*if (!$pageData) {
            throw new \Exception("Page not found");
        }*/

        //alternative values if $pageData is null
        $title = $pageData ? $pageData->getTitle() : "Inscription";
        $description = $pageData ? $pageData->getDescription() : "Page d'inscription";
        $content = $pageData ? $pageData->getContent() : "";

        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $view = new View("User/register.php", "front.php");
        $view->addData("title", $title);
        $view->addData("description", $description);
        $view->addData("content", $content);
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("formData", $formData);

        echo $view->render();
    }

    public function success(): void
    {
        $session = new Session();
        if ($session->isLogged()) {
            header('Location: /');
            exit();
        }
        $view = new View('User/register_success.php', 'front.php');
        $view->render();
    }
}
