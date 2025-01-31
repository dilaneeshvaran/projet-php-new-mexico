<?php

namespace App\Controllers;

use App\Repositories\PageRepository;
use App\Repositories\GroupRepository;
use App\Services\GroupService;
use App\Models\Group;
use App\Core\View;
use App\Core\SQL;
use App\Core\Session;

class GroupController {

    private UploadService $uploadService;

    private PageRepository $pageRepository;

    private GroupRepository $GroupRepository;

    private GroupService $groupService;
    
    public function __construct() {
        $db = new SQL();
        $this->pageRepository = new PageRepository($db);
        $this->GroupRepository = new GroupRepository($db);
        $this->groupService = new GroupService($this->GroupRepository);
    }

    public function index(array $errors = [], array $formData = []): void
    {
        if(!(new Session())->isLogged()) {
            header('Location: /login');
            exit();
        }
        $groupId = $this->retrieveGroupId();
        $group = $this->groupService->getGroupById((int)$groupId);
        if ($group) {
            $this->renderGroupPage([], $groupId, $group);
        } else {
            //TODO : group not found
            $this->renderGroupPage(['Group not found'], $groupId);
        }
    }

    public function post(): void
    {
        $errors = [];
        $formData = $_POST;
        
    }

    private function renderGroupPage(array $errors = [], int $groupId, ?Group $group = null): void
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

        $view = new View("Group/group.php", "front.php");
        $view->addData("title", $title);
        $view->addData("description", $description);
        $view->addData("content", $content);
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("group", $group);
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