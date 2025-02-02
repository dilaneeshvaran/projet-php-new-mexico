<?php

namespace App\Controllers;

use App\Repositories\GroupJoinRequestRepository;
use App\Repositories\UserGroupRepository;
use App\Services\GroupJoinRequestService;
use App\Repositories\UserRepository;
use App\Core\Session;
use App\Core\View;
use App\Core\SQL;
use App\Models\UserGroup;

class GroupJoinRequestController {
    private GroupJoinRequestRepository $groupJoinRequestRepository;
    private GroupJoinRequestService $groupJoinRequestService;
    private UserRepository $userRepository;
    private UserGroupRepository $userGroupRepository;

    public function __construct() {
        $db = new SQL();
        $this->groupJoinRequestRepository = new GroupJoinRequestRepository($db);    
        $this->userRepository = new UserRepository($db);
        $this->userGroupRepository = new UserGroupRepository($db);
        $this->groupJoinRequestService = new GroupJoinRequestService($this->groupJoinRequestRepository, $this->userRepository, $this->userGroupRepository);
    }

    public function index(): void
    {
        if(!(new Session())->isLogged()) {
            header('Location: /login');
            exit();
        }
        $groupId = $this->retrieveGroupId();
        $userId = (new Session())->getUserId();

        if (!$this->userGroupRepository->isMember((int)$groupId, (int)$userId)) {
            $this->renderView(["Vous n'êtes pas membre de ce groupe !"],[]);
            return;
        }
        $userRole = $this->userGroupRepository->getUserRole((int)$groupId, (int)$userId);
        if ($userRole !== 'admin') {
            $this->renderView(["Vous n'avez pas accès à ce groupe !"],[]);
            return;
        }
        $requests = $this->groupJoinRequestService->getJoinRequestsByGroupId($groupId);
        $this->renderView([], $requests, $groupId);
    }

    public function processRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit();
        }

        $csrfToken = $_POST['csrf_token'] ?? '';
        if ($csrfToken !== $_SESSION['csrf_token']) {
            die("Invalid CSRF token");
        }

        $requestId = $_POST['requestId'] ?? null;
        $status = $_POST['status'] ?? null;
        $groupId = $this->retrieveGroupId();

        if (!$requestId || !$status) {
            die("Invalid request");
        }

        $success = $this->groupJoinRequestService->processRequest((int)$requestId, $status, (int)$groupId);
        header("Location: /group/{$groupId}/join-requests");
        exit();
    }

    private function renderView(array $errors = [], ?array $requests, ?int $groupId): void {
        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $view = new View("Group/manage_group_join_requests.php", "front.php");
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("requests", $requests);
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
}