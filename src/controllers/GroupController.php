<?php

namespace App\Controllers;

use App\Repositories\PageRepository;
use App\Repositories\GroupRepository;
use App\Services\UserGroupService;
use App\Services\GroupService;
use App\Repositories\UserGroupRepository;
use App\Models\Group;
use App\Core\View;
use App\Core\SQL;
use App\Core\Session;

class GroupController {

    private UploadService $uploadService;

    private PageRepository $pageRepository;

    private GroupRepository $GroupRepository;

    private GroupService $groupService;

    private UserGroupService $userGroupService;
    
    public function __construct() {
        $db = new SQL();
        $this->pageRepository = new PageRepository($db);
        $this->GroupRepository = new GroupRepository($db);
        $this->groupService = new GroupService($this->GroupRepository);
        $this->userGroupService = new UserGroupService(new UserGroupRepository($db));
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

        $view = new View("Group/group.php", "front.php");
        $view->addData("title", $title);
        $view->addData("description", $description);
        $view->addData("content", $content);
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("group", $group);
        echo $view->render();
    }

    

    // CREATE GROUP
    public function renderCreateGroupPage(array $errors = [],array $formData = []): void
    {
        $pageId = 1;
        $pageData = $this->pageRepository->findOneById($pageId);

        //alternative values if $pageData is null
        $title = $pageData ? $pageData->getTitle() : "Upload Photo";
        $description = $pageData ? $pageData->getDescription() : "Upload your photos";
        $content = $pageData ? $pageData->getContent() : "";

        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $view = new View("Group/create_group.php", "front.php");
        $view->addData("title", $title);
        $view->addData("description", $description);
        $view->addData("content", $content);
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        echo $view->render();
    }


    public function create(): void
{
    $errors = [];
    try {
        // CSRF validation
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: /group/create");
        exit();
        }

        $formData = $_POST;
        $result = $this->groupService->createGroup($formData);
        if (!empty($result['groupId'])) {
            $userId = $_SESSION['user_id'] ?? null;
            $groupId = $result['groupId'];

            if ($userId) {
                $errors = $this->userGroupService->adminCreateGroup($userId, $groupId);
            } else {
                $errors[] = "Utilisateur non authentifié.";
            }
        } else {
            $errors = $result['errors'] ?? [];
        }

        if (empty($errors)) {
            //redirect to group page : TODO
            header("Location: /group/create/success");
            exit();
        }

    } catch (\Exception $e) {
        $errors[] = $e->getMessage();
        }
        $this->renderCreateGroupPage($errors, $formData);

}


    public function createSuccess(): void
    {
        $view = new View('Group/create_group_success.php', 'front.php');
        echo $view->render();
    }


    //SETTINGS

    public function settings(): void
    {
        if(!(new Session())->isLogged()) {
            header('Location: /login');
            exit();
        }
        $groupId = $this->retrieveGroupId();
        $group = $this->groupService->getGroupById((int)$groupId);
        if ($group) {
            $this->renderGroupSettingsPage([], $groupId, $group);
        } else {
            //TODO : group not found
            $this->renderGroupSettingsPage(['Group not found'], $groupId);
        }
    }

    public function renderGroupSettingsPage(array $errors = [],int $groupId, ?Group $group = null): void
    {
        $pageId = 1;
        $pageData = $this->pageRepository->findOneById($pageId);

        //alternative values if $pageData is null
        $title = $pageData ? $pageData->getTitle() : "Upload Photo";
        $description = $pageData ? $pageData->getDescription() : "Upload your photos";
        $content = $pageData ? $pageData->getContent() : "";

        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $view = new View("Group/group_settings.php", "front.php");
        $view->addData("title", $title);
        $view->addData("description", $description);
        $view->addData("content", $content);
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("groupId", $groupId);
        $view->addData("group", $group);
        echo $view->render();
    }

    public function settingsSave(): void
    {
        $groupId = $this->retrieveGroupId();
        $errors = [];
        $formData = $_POST;
        try {
            // CSRF validation
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
            $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            header("Location: /group/$groupId/settings");
            exit();
            }

            if (!$groupId || !is_numeric($groupId)) {
                $errors[] = "ID du groupe invalide.";
            } else {
                $errors = $this->groupService->updateGroup((int) $groupId, $formData);
            }

            if (empty($errors)) {
                //redirect to group page : TODO
                header("Location: /group/$groupId/settings/success");
                exit();
            }

        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
            }
            $this->renderGroupSettingsPage($errors, $groupId);

    }

    public function settingsSuccess(): void
    {
        $view = new View('Group/group_settings_success.php', 'front.php');
        $groupId = $this->retrieveGroupId();
        $view->addData('groupId', $groupId);
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