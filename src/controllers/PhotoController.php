<?php

namespace App\Controllers;

use App\Repositories\PageRepository;
use App\Repositories\PhotoRepository;
use App\Repositories\GroupRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserGroupRepository;
use App\Services\PhotoService;
use App\Services\GroupService;
use App\Models\Group;
use App\Core\View;
use App\Core\SQL;
use App\Core\Session;

class PhotoController {

    private PageRepository $pageRepository;

    private PhotoRepository $photoRepository;

    private PhotoService $photoService;

    private GroupRepository $groupRepository;

    private GroupService $groupService;

    private UserRepository $userRepository;

    private UserGroupRepository $userGroupRepository;
    
    public function __construct() {
        $db = new SQL();
        $this->userGroupRepository = new UserGroupRepository($db);
        $this->photoRepository = new PhotoRepository($db);
        $this->userRepository = new UserRepository($db);
        $this->photoService = new PhotoService($this->photoRepository,$this->userRepository);
        $this->pageRepository = new PageRepository($db);
        $this->groupRepository = new GroupRepository($db);
        $this->groupService = new GroupService($this->groupRepository, $this->userGroupRepository);
    }

    public function index(array $errors = [], array $formData = []): void
    {
        if(!(new Session())->isLogged()) {
            header('Location: /login');
            exit();
        }
        $groupId = $this->retrieveGroupId();
        $userId = (new Session())->getUserId();

        if (!$this->userGroupRepository->isMember((int)$groupId, (int)$userId)) {
            header('Location: /');
            exit();
        }
        $this->renderView();
    }

    private function renderView(array $errors = [], array $formData = []): void
    {
        $pageId = 1;
        $pageData = $this->pageRepository->findOneById($pageId);

        //alternative values if $pageData is null
        $title = $pageData ? $pageData->getTitle() : "Photos";
        $description = $pageData ? $pageData->getDescription() : "Voir les photos";
        $content = $pageData ? $pageData->getContent() : "";

        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $groupId = $this->retrieveGroupId();
        $userId = $_SESSION['user_id'] ?? 0;

        //check if user is the creator of the photo or admin of the group
        $userRole = $this->userGroupRepository->getUserRole($groupId, $userId);
        
        $groupAccess = $this->userGroupRepository->getGroupAccess($groupId, $userId) ?? "No Access";
        $photos = $this->photoService->fetchPhotosByGroupId($groupId);
        $group = $this->groupService->getGroupById($groupId);
        
        $view = new View("photo/group_photos.php", "front.php");
        $view->addData("title", $title);
        $view->addData("description", $description);
        $view->addData("content", $content);
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("photos", $photos);
        $view->addData("group", $group);
        $view->addData("userRole", $userRole);
        $view->addData("group_access", $groupAccess);
        $view->addData("userId", $userId);
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
}