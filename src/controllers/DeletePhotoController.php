<?php

namespace App\Controllers;

use App\Core\View;
use App\Services\DeletePhotoService;
use App\Repositories\PhotoRepository;
use App\Repositories\UserGroupRepository;
use App\Core\SQL;
use App\Core\Session;

class DeletePhotoController
{
    private DeletePhotoService $deletePhotoService;

    public function __construct()
    {
        $db = new SQL();
        $this->deletePhotoService = new DeletePhotoService(new PhotoRepository($db), new UserGroupRepository($db));
    }

    public function delete(): void
    {
        $session = new Session();
        if (!$session->isLogged()) {
            header('Location: /login');
            exit();
        }
        
        $errors = [];
        $groupId = $this->retrieveGroupId();
        $photoId = $this->retrievePhotoId();

        // CSRF validation
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
                $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                header("Location: /group/$groupId/photos");
                exit();
            }

        try {
            if (!$photoId || !$groupId) {
                throw new \Exception('Données incorrectes.');
            }

            $userId = $session->getUserId();

            $errors = $this->deletePhotoService->deletePhoto($photoId, $groupId, $userId);

            if (empty($errors)) {
                $this->renderSuccessView($errors, $groupId);
                exit();
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        //redirect back with errors if occurred
        $this->renderSuccessView($errors, $groupId);
    }

    private function renderSuccessView(array $errors = [], int $groupId): void
    {
        $session = new Session();
        if (!$session->isLogged()) {
            header('Location: /login');
            exit();
        }
        $view = new View("photo/delete_success.php", "front.php");
        $view->addData("errors", $errors);
        $view->addData("groupId", $groupId);
        echo $view->render();
    }

    private function retrieveGroupId(): int {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST['groupId'] ?? 0;
        }

        $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $groupIndex = array_search('group', $urlParts);
        return ($groupIndex !== false && isset($urlParts[$groupIndex + 1])) ? (int)$urlParts[$groupIndex + 1] : 0;
    }

    private function retrievePhotoId(): int {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST['photoId'] ?? 0;
        }
    
        $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $photoIndex = array_search('photo', $urlParts);
        return ($photoIndex !== false && isset($urlParts[$photoIndex + 1])) ? (int)$urlParts[$photoIndex + 1] : 0;
    }
}