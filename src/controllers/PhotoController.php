<?php

namespace App\Controllers;

use App\Services\UploadService;
use App\Models\PhotoValidator;
use App\Repositories\PageRepository;
use App\Repositories\UserGroupRepository;
use App\Repositories\PhotoRepository;
use App\Core\View;
use App\Core\SQL;
use App\Core\Session;

class PhotoController {

    private UploadService $uploadService;

    private PageRepository $pageRepository;

    private UserGroupRepository $userGroupRepository;
    
    public function __construct() {
        $db = new SQL();
        $this->userGroupRepository = new UserGroupRepository($db);
        $this->uploadService = new UploadService(new PhotoRepository($db),new UserGroupRepository($db));
        $this->pageRepository = new PageRepository($db);
    }


    public function index(array $errors = [], array $formData = []): void
    {
        if(!(new Session())->isLogged()) {
            header('Location: /login');
            exit();
        }
        $this->renderUploadPage();
    }

    public function post(): void
    {
        $errors = [];
        $formData = $_POST;
        $fileData = $_FILES['photo'] ?? null;
        $groupId = $_POST['group_id'] ?? null;

        try {
            // CSRF validation
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
                $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                header('Location: /upload');
                exit();
            }

            if ($fileData) {
                $errors = $this->uploadService->uploadPhoto($fileData, $groupId, $_SESSION['user_id']);
            } else {
                $errors[] = 'No file uploaded.';
            }

        
            if (empty($errors)) {
                //redirect to group page : TODO
                header('Location: /upload/success');
                exit();
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        $this->renderUploadPage($errors, $formData);
    }

    private function renderUploadPage(array $errors = [], array $formData = []): void
    {
        $pageId = 1;
        $pageData = $this->pageRepository->findOneById($pageId);

        //alternative values if $pageData is null
        $title = $pageData ? $pageData->getTitle() : "Upload Photo";
        $description = $pageData ? $pageData->getDescription() : "Upload your photos";
        $content = $pageData ? $pageData->getContent() : "";

        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        //fetch groups of the user
        $userId = $_SESSION['user_id'] ?? null;
        $groups = $userId ? $this->userGroupRepository->getGroupsByUserId($userId) : [];

        $view = new View("photo/upload.php", "front.php");
        $view->addData("title", $title);
        $view->addData("description", $description);
        $view->addData("content", $content);
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("formData", $formData);
        $view->addData("groups", $groups);
        $view->addData("allowedTypes", PhotoValidator::getAllowedTypes());
        $view->addData("maxFileSize", PhotoValidator::getMaxFileSize());
        echo $view->render();
    }

    public function success(): void
    {
        $view = new View('photo/upload_success.php', 'front.php');
        $view->render();
    }
}