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

class UserGroupController {


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
        if ($groupId) {
            $this->renderGroupMembersPage([], $groupId);
        } else {
            //TODO : group not found
            $this->renderGroupMembersPage(['Group not found'], $groupId);
        }
    }

    private function renderGroupMembersPage(array $errors = [], int $groupId): void
    {
        $pageId = 1;
        $pageData = $this->pageRepository->findOneById($pageId);

        //alternative values if $pageData is null
        $title = $pageData ? $pageData->getTitle() : "Upload Photo";
        $description = $pageData ? $pageData->getDescription() : "Upload your photos";
        $content = $pageData ? $pageData->getContent() : "";

        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $members = $this->userGroupService->getUsersByGroupId($groupId);

        $view = new View("Group/group_members.php", "front.php");
        $view->addData("title", $title);
        $view->addData("description", $description);
        $view->addData("content", $content);
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("groupId", $groupId);
        $view->addData("members", $members);
        echo $view->render();
    }

    //manage users
    public function manageMember(): void
    {
        if(!(new Session())->isLogged()) {
            header('Location: /login');
            exit();
        }
        $groupId = $this->retrieveGroupId();
        $memberId = $this->retrieveMemberId();
        if ($groupId && $memberId) {
            $memberDetails = $this->userGroupService->getGroupMemberDetails($groupId, $memberId);
            if (!$memberDetails) {
                $this->renderManageMemberPage(['Member not found or not part of the group'], $groupId, $memberId);
            } else {
                $this->renderManageMemberPage([], $groupId, $memberId, $memberDetails);
            }
        } else {
            $this->renderManageMemberPage(['Group not found'], $groupId, $memberId);
        }
    }

    public function renderManageMemberPage(array $errors = [],  int $groupId, int $memberId, ?array $memberDetails = null): void
    {
        $pageId = 1;
        $pageData = $this->pageRepository->findOneById($pageId);

        //alternative values if $pageData is null
        $title = $pageData ? $pageData->getTitle() : "Upload Photo";
        $description = $pageData ? $pageData->getDescription() : "Upload your photos";
        $content = $pageData ? $pageData->getContent() : "";

        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $view = new View("Group/manage_group_member.php", "front.php");
        $view->addData("title", $title);
        $view->addData("description", $description);
        $view->addData("content", $content);
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("groupId", $groupId);
        $view->addData("memberId", $memberId);
        $view->addData("memberDetails", $memberDetails);
        echo $view->render();
    }

    //UPDATE GROUP ACCESS
    public function updateAccess(): void
    {
        $session = new Session();
        if (!$session->isLogged()) {
            header('Location: /login');
            exit();
        }

        $errors = [];
        $currentUserId =  $_SESSION['user_id'];
        $groupId = $this->retrieveGroupId();
        $memberId = $this->retrieveMemberId();

        // CSRF validation
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: /group/$groupId/member/$memberId/manage");
        exit();
        }

        try {
            if (!$groupId || !is_numeric($groupId) || !$memberId || !is_numeric($memberId)) {
                echo $groupId;
                echo $memberId;
                $errors[] = "ID du groupe ou de l'utilisateur invalide.";
            }
            else {
                $userRole = $this->userGroupService->getUserRole($groupId, $currentUserId);
            if ($userRole !== 'admin') {
                header("Location: /group/$groupId/member/$memberId/manage");
                exit();
            }

            $update_access = $_POST['update_access'] ?? '';
            $newAccess = $update_access === 'give_write' ? 'writer' : 'reader';
            
            if ($this->userGroupService->updateMemberAccess($groupId, $memberId, $newAccess)) {
                header("Location: /group/$groupId/member/$memberId/manage");
                exit();
            } else {
                $errors['Failed to update access'];
            }
            }

            if (empty($errors)) {
                //redirect to group page : TODO
                header("Location: /group/$groupId/member/$memberId/manage");
                exit();
            }

        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }
        $this->renderManageMemberPage($errors, $groupId, $memberId);
    }

    //DELETE
    public function removeMember(): void
    {
        $session = new Session();
    if (!$session->isLogged()) {
        header('Location: /login');
        exit();
    }
    $currentUserId =  $_SESSION['user_id'];
    $groupId = $this->retrieveGroupId();
    $memberId = $this->retrieveMemberId();
            $errors = [];

        // CSRF validation
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: /group/$groupId/member/$memberId/manage");
        exit();
        }

            $userRole = $this->userGroupService->getUserRole($groupId, $currentUserId);
            if ($userRole !== 'admin') {
                header("Location: /group/$groupId/member/$memberId/manage");
                exit();
            }

        try {
            if ($this->userGroupService->removeMemberFromGroup($groupId, $memberId)) {
                header("Location: /group/$groupId/member/$memberId/manage");
                exit();
            } else {
                $errors['Failed to remove member'];
            }

            if (empty($errors)) {
                //redirect to group page : TODO
                header("Location: /group/$groupId/member/$memberId/manage");
                exit();
            }

        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
            }
            $this->renderManageMemberPage($errors, $groupId);
    }


    private function retrieveGroupId(): int {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST['groupId'] ?? 0;
        }

        $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $groupIndex = array_search('group', $urlParts);
        return ($groupIndex !== false && isset($urlParts[$groupIndex + 1])) ? (int)$urlParts[$groupIndex + 1] : 0;
    }

    private function retrieveMemberId(): int {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST['memberId'] ?? 0;
        }
    
        $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $memberIndex = array_search('member', $urlParts);
        return ($memberIndex !== false && isset($urlParts[$memberIndex + 1])) ? (int)$urlParts[$memberIndex + 1] : 0;
    }
}