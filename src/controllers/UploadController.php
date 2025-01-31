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

class UploadController {

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
        $groupId = $this->retrieveGroupId();
        $this->renderUploadPage([],[],$groupId);
    }

    public function post(): void
    {
        $errors = [];
        $formData = $_POST;
        $fileData = $_FILES['photo'] ?? null;
        $groupId = $this->retrieveGroupId();

        try {
            // CSRF validation
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
                $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                header("Location: /group/$groupId/upload");
                exit();
            }

            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');

            $userRole= $this->userGroupRepository->getUserRole($groupId,$_SESSION['user_id']);
            $groupAccess = $this->userGroupRepository->getGroupAccess($groupId,$_SESSION['user_id']);

            if ($userRole === null) {
                $errors[] = 'You are not a member of this group.';
            }
            if ($groupAccess !== 'write') {
                $errors[] = 'You do not have permission to upload photos to this group.';
            }

            if ($fileData) {
                $errors = $this->uploadService->uploadPhoto($fileData, $groupId, $_SESSION['user_id'],$title, $description);
            } else {
                $errors[] = 'No file uploaded.';
            }

        
            if (empty($errors)) {
                //redirect to group page : TODO
                header("Location: /group/$groupId/upload/success");
                exit();
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        $this->renderUploadPage($errors, $formData, $groupId);
    }

    private function renderUploadPage(array $errors = [], array $formData = [], int $groupId): void
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

        $view = new View("photo/upload.php", "front.php");
        $view->addData("title", $title);
        $view->addData("description", $description);
        $view->addData("content", $content);
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("formData", $formData);
        $view->addData("groupId", $groupId);
        $view->addData("allowedTypes", PhotoValidator::getAllowedTypes());
        $view->addData("maxFileSize", PhotoValidator::getMaxFileSize());
        echo $view->render();
    }

    public function success(): void
    {
        $view = new View('photo/upload_success.php', 'front.php');
        $groupId = $this->retrieveGroupId();
        $view->addData('groupId', $groupId);
        $view->render();
    }

    private function retrieveGroupId(): int {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST['groupId'] ?? 0;
        } 

        $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $groupIndex = array_search('group', $urlParts);
        return ($groupIndex !== false && isset($urlParts[$groupIndex + 1])) ? (int)$urlParts[$groupIndex + 1] : 0;
    }
}