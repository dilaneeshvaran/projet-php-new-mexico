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

    private GroupRepository $groupRepository;

    private GroupService $groupService;

    private UserGroupService $userGroupService;

    private UserGroupRepository $userGroupRepository;
    
    public function __construct() {
        $db = new SQL();
        $this->pageRepository = new PageRepository($db);
        $this->groupRepository = new GroupRepository($db);
        $this->userGroupRepository = new UserGroupRepository($db);
        $this->userGroupService = new UserGroupService($this->userGroupRepository);
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

        if ($groupId) {
            $this->renderGroupMembersPage([], $groupId);
        } else {
            //TODO : group not found
            $this->renderGroupMembersPage(['Groupe invalide'], $groupId);
        }
    }

    private function renderGroupMembersPage(array $errors = [], int $groupId): void
    {
        $pageId = 1;
        $pageData = $this->pageRepository->findOneById($pageId);

        //alternative values if $pageData is null
        $title = $pageData ? $pageData->getTitle() : "Membres";
        $description = $pageData ? $pageData->getDescription() : "Membre du groupe";
        $content = $pageData ? $pageData->getContent() : "";

        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $members = $this->userGroupService->getUsersByGroupId($groupId);
        $userId = $_SESSION['user_id'];
        $userRole = $this->userGroupService->getUserRole($groupId, $userId);

        $view = new View("Group/group_members.php", "front.php");
        $view->addData("title", $title);
        $view->addData("description", $description);
        $view->addData("content", $content);
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("groupId", $groupId);
        $view->addData("members", $members);
        $view->addData("userRole", $userRole);
        echo $view->render();
    }

    //manage users
    public function manageMember(): void
    {
        if(!(new Session())->isLogged()) {
            header('Location: /login');
            exit();
        }
        $userId = (new Session())->getUserId();
        $groupId = $this->retrieveGroupId();
        if (!$this->userGroupRepository->isMember((int)$groupId, (int)$userId)) {
            header('Location: /');
            exit();
        }
        $userRole = $this->userGroupRepository->getUserRole((int)$groupId, (int)$userId);
        if ($userRole !== 'admin') {
            header('Location: /');
            exit();
        }
        $memberId = $this->retrieveMemberId();
        if ($groupId && $memberId) {
            $memberDetails = $this->userGroupService->getGroupMemberDetails($groupId, $memberId);
            if (!$memberDetails) {
                $this->renderManageMemberPage(['Utilisateur non trouvé dans le groupe'], $groupId, $memberId);
            } else {
                $this->renderManageMemberPage([], $groupId, $memberId, $memberDetails);
            }
        } else {
            $this->renderManageMemberPage(['Groupe Invalide'], $groupId, $memberId);
        }
    }

    public function renderManageMemberPage(array $errors = [],  int $groupId, int $memberId, ?array $memberDetails = null): void
    {
        $pageId = 1;
        $pageData = $this->pageRepository->findOneById($pageId);

        //alternative values if $pageData is null
        $title = $pageData ? $pageData->getTitle() : "Gesion du membre";
        $description = $pageData ? $pageData->getDescription() : "Gestion du membre";
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

        $userId = (new Session())->getUserId();

        if (!$this->userGroupRepository->isMember((int)$groupId, (int)$userId)) {
            header('Location: /');
            exit();
        }
        $userRole = $this->userGroupRepository->getUserRole((int)$groupId, (int)$userId);
        if ($userRole !== 'admin') {
            header('Location: /');
            exit();
        }

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
                $errors[] = "Mise à jour de l\'accès échouée";
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

            $userId = (new Session())->getUserId();

        if (!$this->userGroupRepository->isMember((int)$groupId, (int)$userId)) {
            header('Location: /');
            exit();
        }
        $userRole = $this->userGroupRepository->getUserRole((int)$groupId, (int)$userId);
        if ($userRole !== 'admin') {
            header('Location: /');
            exit();
        }

        // CSRF validation
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: /group/$groupId/members");
        exit();
        }

            $userRole = $this->userGroupService->getUserRole($groupId, $currentUserId);
            if ($userRole !== 'admin') {
                header("Location: /group/$groupId/members");
                exit();
            }

        try {
            if ($this->userGroupService->removeMemberFromGroup($groupId, $memberId)) {
                header("Location: /group/$groupId/members");
                exit();
            } else {
                $errors[] = 'Failed to remove member';
            }

        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
            }
            $this->renderManageMemberPage($errors, $groupId);
    }


    //JOIN GROUP

    public function renderJoinRequest(array $errors = [],  ?array $groups = []): void
    {
        $session = new Session();
        if (!$session->isLogged()) {
            header('Location: /login');
            exit();
        }
        $pageId = 1;
        $pageData = $this->pageRepository->findOneById($pageId);

        //alternative values if $pageData is null
        $title = $pageData ? $pageData->getTitle() : "Demandes pour rejoindre";
        $description = $pageData ? $pageData->getDescription() : "Demandes pour rejoindre";
        $content = $pageData ? $pageData->getContent() : "";

        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $view = new View("Group/search_group.php", "front.php");
        $view->addData("title", $title);
        $view->addData("description", $description);
        $view->addData("content", $content);
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("groups", $groups);
        echo $view->render();
    }

    public function joinGroup(): void
    {
        $session = new Session();
        if (!$session->isLogged()) {
            header('Location: /login');
            exit();
        }
        $currentUserId =  $_SESSION['user_id'];
        $groupId = $this->retrieveGroupId();
        $errors = [];

        // CSRF validation
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: /group/search/result");
        exit();
        }

            if (empty($errors)) {
                $errors[] = $this->userGroupService->joinGroup($groupId, $currentUserId);
            }

            if (empty($errors)) {
                //redirect to group page : TODO
                header("Location: /group/$groupId");
                exit();
            }

            $searchGroupName = $_POST['searchGroupName'] ?? '';
            $userId = $_SESSION['user_id'] ?? null;


            try {
                $groups = $this->groupService->getGroupByName($searchGroupName, $userId);
                $this->renderJoinRequest($errors, $groups);
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
                $this->renderJoinRequest($errors);
            }
    }
    

    //LEAVE GROUP
    public function renderMemberSettings(?array $errors = []):void{
        $session = new Session();
        if (!$session->isLogged()) {
            header('Location: /login');
            exit();
        }
        $pageId = 1;
        $pageData = $this->pageRepository->findOneById($pageId);

        $groupId = $this->retrieveGroupId();
        $memberId = $_SESSION['user_id'];

        //alternative values if $pageData is null
        $title = $pageData ? $pageData->getTitle() : "Paramètres du membre";
        $description = $pageData ? $pageData->getDescription() : "Gerer les paramètres du membre";
        $content = $pageData ? $pageData->getContent() : "";

        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $view = new View("Group/member_settings.php", "front.php");
        $view->addData("title", $title);
        $view->addData("description", $description);
        $view->addData("content", $content);
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("groupId", $groupId);
        $view->addData("memberId", $memberId);
        echo $view->render();
    }

    public function leaveGroup(): void
    {
        $session = new Session();
        if (!$session->isLogged()) {
            header('Location: /login');
            exit();
        }
        $currentUserId =  $_SESSION['user_id'];
        $groupId = $this->retrieveGroupId();
        $errors = [];

        $userId = (new Session())->getUserId();

        if (!$this->userGroupRepository->isMember((int)$groupId, (int)$userId)) {
            header('Location: /');
            exit();
        }
        // CSRF valida0tion
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: /group/member/settings");
        exit();
        }

        try {
            if (!$groupId || !is_numeric($groupId)) {
                $errors[] = "ID du groupe invalide.";
            }
            else {
                if (!$this->userGroupService->leaveGroup($groupId, $currentUserId)) {
                    $errors[] = "Erreur lors de la sortie du groupe.";
                } else {
                    header("Location: /");
                    exit();
                }
            }

        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }
        $this->renderMemberSettings($errors);
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