<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\ResetPasswordTokenRepository;
use App\Services\ForgotPasswordService;
use App\Core\View;
use App\Core\SQL;
use App\Core\Session;
class ForgotPasswordController
{
    private ForgotPasswordService $forgotPasswordService;
    private UserRepository $userRepository;
    private ResetPasswordTokenRepository $resetPasswordTokenRepository;

    public function __construct()
    {
        $db = new SQL();
        $this->forgotPasswordService = new ForgotPasswordService(new UserRepository($db), new ResetPasswordTokenRepository($db));
    }

    public function index(): void
    {
        $session = new Session();
        if ($session->isLogged()) {
            header('Location: /');
            exit();
        }
        $this->renderView();
        
    }

    public function success(): void
    {
        $session = new Session();
        if ($session->isLogged()) {
            header('Location: /');
            exit();
        }
        $view = new View('User/forgot_password_success.php', 'front.php');
        $view->render();
    }

    public function submit(): void
    {
        $session = new Session();
        if ($session->isLogged()) {
            header('Location: /login');
            exit();
        }
        $errors = [];
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
                $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    header('Location: /forgot-password');
                    exit();
            }
        try {
            $email = $_POST['email'] ?? '';
            $this->forgotPasswordService->handleForgotPassword($email);
            header('Location: /forgot-password/success');
            exit();
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        $this->renderView($errors);
    }

    private function renderView(array $errors = []): void
    {
        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $view = new View('User/forgot_password.php', 'front.php');
        $view->addData("errors", $errors);
        $view->addData("csrf_token", $csrfToken);
        $view->render();
    }
}