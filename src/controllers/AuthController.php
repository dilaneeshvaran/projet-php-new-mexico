<?php

namespace App\Controllers;

use App\Services\LoginService;
use App\Repositories\UserRepository;
use App\Repositories\PageRepository;
use App\Core\SQL;
use App\Core\View;
use App\Core\Session;


class AuthController
{
    private PageRepository $pageRepository;
    private LoginService $loginService;

    public function __construct()
    {
        $db = new SQL();
        $this->loginService = new LoginService(new UserRepository($db));
        $this->pageRepository = new PageRepository($db);
    }

    public function index(): void
    {
        $session = new Session();
        if ($session->isLogged()) {
            header('Location: /');
            exit();
        }
        $this->renderLoginPage();
    }

    public function post(): void
    {
        $errors = [];
        $formData = $_POST;

        try {
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
                $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    header('Location: /');
                    exit();
            }

            $this->loginService->loginUser($formData);
            header('Location: /');
            exit();
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        $this->renderLoginPage($errors, $formData);
    }

    private function renderLoginPage(array $errors = [], array $formData = []): void
    {
        $pageId = 2;
        $pageData = $this->pageRepository->findOneById($pageId);
        /*if (!$pageData) {
            throw new \Exception("Page not found");
        }*/


        //alternative values if $pageData is null
        $title = $pageData ? $pageData->getTitle() : "Connexion";
        $description = $pageData ? $pageData->getDescription() : "Page de connexion";
        $content = $pageData ? $pageData->getContent() : "";

        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $view = new View('User/login.php', 'front.php');
        $view->addData("title", $title);
        $view->addData("description", $description);
        $view->addData("content", $content);
        $view->addData("formData", $formData);
        $view->addData("errors", $errors);
        $view->addData("csrfToken", value: $csrfToken);
        $view->render();
    }

    public function logout(): void
    {
        $session = new Session();
        $session->logout();
        header("Location: /login");
        exit();
    }
}