<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\ResetPasswordTokenRepository;
use App\Services\ResetPasswordService;
use App\Core\View;
use App\Core\SQL;
use App\Core\Session;
class ResetPasswordController
{
    private ResetPasswordService $resetPasswordService;
    private UserRepository $userRepository;
    private ResetPasswordTokenRepository $resetPasswordTokenRepository;

    public function __construct()
    {
        $db = new SQL();
        $this->resetPasswordService = new ResetPasswordService(new UserRepository($db), new ResetPasswordTokenRepository($db));
    }

    public function index(): void
    {
        $session = new Session();
        if ($session->isLogged()) {
            header('Location: /');
            exit();
        }
        $token = $this->retrieveToken();
        //validate token
        if (strlen($token) !== 64 || !ctype_xdigit($token)) {
            die("Lien invalid.");
        }

        $this->renderView([], $token);
    }
    public function success(): void
    {
        $session = new Session();
        if ($session->isLogged()) {
            header('Location: /');
            exit();
        }
        $view = new View('User/reset_password_success.php', 'front.php');
        $view->render();
    }

    public function submit(): void
    {
        
        $errors = [];
        $token = $this->retrieveToken();

        try {
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
                $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                header('Location: /reset-password/'.urlencode($token));
                exit();
            }
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            $errors = $this->resetPasswordService->resetPassword($token, $password, $passwordConfirm);

            if (empty($errors)) {
                header('Location: /reset-password/'.urlencode($token).'/success');
                exit();
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }
        $this->renderView($errors, $token);
    }

    private function retrieveToken(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST['token'] ?? '';
        } 
        $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

        $tokenIndex = array_search('reset-password', $urlParts);
        if ($tokenIndex !== false && isset($urlParts[$tokenIndex + 1])) {
            return $urlParts[$tokenIndex + 1];
        }

        return '';
    }

    public function renderView(array $errors = [], string $token = ''): void
    {

        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $view = new View('User/reset_password.php', 'front.php');
        $view->addData('errors', $errors);
        $view->addData('token', $token);
        $view->addData('csrfToken', $csrfToken);
        $view->render();
    }
}
