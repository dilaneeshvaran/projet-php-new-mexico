<?php

namespace App\Controllers;

use App\Repositories\InviteMemberRepository;
use App\Repositories\UserGroupRepository;
use App\Services\InviteMemberService;
use App\Repositories\UserRepository;
use App\Repositories\GroupRepository;
use App\Core\Session;
use App\Core\View;
use App\Core\SQL;
use App\Models\MemberInvitation;

class InvitationController {  
    private InviteMemberRepository $inviteMemberRepository;
    private InviteMemberService $inviteMemberService;
    private UserRepository $userRepository;
    private UserGroupRepository $userGroupRepository;
    private GroupRepository $groupRepository;

    public function __construct() {
        $db = new SQL();
        $this->inviteMemberRepository = new InviteMemberRepository($db);    
        $this->userRepository = new UserRepository($db);
        $this->userGroupRepository = new UserGroupRepository($db);
        $this->groupRepository = new GroupRepository($db);
        $this->inviteMemberService = new InviteMemberService($this->inviteMemberRepository, $this->userRepository, $this->userGroupRepository, $this->groupRepository);
    }

    public function index(): void
    {
        if(!(new Session())->isLogged()) {
            header('Location: /login');
            exit();
        }
        $userId = (new Session())->getUserId();
        $invitations = $this->inviteMemberService->getPendingInvitationsAndGroupDetails($userId);
        $this->renderView([], $userId, $invitations);
    }

    public function respondInvitation(): void
    {
        $errors = [];
        $userId = (new Session())->getUserId();
        $invitationId = $this->retrieveInvitationId();
        $action = $_POST['action'] ?? null;

        try {
            // CSRF validation
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
                $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                header("Location: /invitations");
                exit();
            }
        
            //process here
            if ($invitationId && $action) {
                $errors = $this->inviteMemberService->respondInvitation($invitationId, $userId, $action);
            }

            if (empty($errors)) {
                //redirect to group page : TODO
                $invitations = $this->inviteMemberService->getPendingInvitationsAndGroupDetails($userId);
                $errors[] = "Invitation traitée avec succès";
                $this->renderView($errors, $userId, $invitations);
                exit();
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }
        $invitations = $this->inviteMemberService->getPendingInvitationsAndGroupDetails($userId);
        $this->renderView($errors, $userId, $invitations);
    }

    private function renderView(array $errors = [], ?int $userId, array $invitations): void {
        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $view = new View("Group/received_invitations.php", "front.php");
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("userId", $userId);
        $view->addData("invitations", $invitations);
        echo $view->render();
    }


    private function retrieveInvitationId(): int {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST['invitationId'] ?? 0;
        }

        $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $groupIndex = array_search('invitations', $urlParts);
        return ($groupIndex !== false && isset($urlParts[$groupIndex + 1])) ? (int)$urlParts[$groupIndex + 1] : 0;
    }
}