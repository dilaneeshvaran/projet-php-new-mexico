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

class InviteMemberController {  
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
        $allUsers = $this->userRepository->findAllUsers();
        $this->renderView([], [], $groupId);
    }

    public function sendInvitation(): void
    {
        $errors = [];
        $groupId = $this->retrieveGroupId();
        $memberId = $this->retrieveMemberId();

        try {
            // CSRF validation
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
                $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                header("Location: /group/$groupId/invite-member");
                exit();
            }

            $userRole= $this->userGroupRepository->getUserRole($groupId,$_SESSION['user_id']);

            if ($userRole === null) {
                $errors[] = "Vous n'êtes pas membre de ce groupe.";
            } else if ($userRole !== 'admin') {
                $errors[] = "Vous n'avez pas le permission.";
            }
        
            //process here
            $errors = $this->inviteMemberService->processInvitation($groupId, $memberId);

            if (empty($errors)) {
                //redirect to group page : TODO
                $this->renderView(["Invitation envoyée"], [], $groupId);
                exit();
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }
        $allUsers = $this->userRepository->findAllUsers();
        $this->renderView($errors, [], $groupId);
    }

    public function searchUser(): void
    {
        $session = new Session();
        if (!$session->isLogged()) {
            header('Location: /login');
            exit();
        }
    
        $groupId = $this->retrieveGroupId();
        $errors = [];
        $searchUserKeyword = $_POST['searchUser'] ?? '';

        $userRole= $this->userGroupRepository->getUserRole($groupId, $session->getUserId());

            if ($userRole === null) {
                $errors[] =  "Vous n'êtes pas membre de ce groupe.";
            } else if ($userRole !== 'admin') {
                $errors[] = "Vous n'avez pas le permission.";
            }
    
            try {
                $users = empty($searchUserKeyword) ? [] : $this->userRepository->searchUsersByNameOrEmail($searchUserKeyword);

                foreach ($users as $user) {
                    //check again if user is already a member
                    $isMember = $this->userGroupRepository->isMember($groupId, $user->getId());
                    
                    if ($isMember) {
                        $user->invitationStatus = 'member';
                    } else {
                        //get latest invitation status if not already member
                        $latestInvitation = $this->inviteMemberRepository->findLatestInvitationStatus($groupId, $user->getId());
                        
                        //show only pending invitations bcoz if accepted but not member, they left
                        if ($latestInvitation && $latestInvitation['status'] === 'pending') {
                            $user->invitationStatus = 'pending';
                        } else {
                            //maybe not invited or they accepted then left
                            $user->invitationStatus = null;
                        }
                    }
                }
        
                $this->renderView($errors, $users, $groupId);
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
                $this->renderView($errors, [], $groupId);
            }   
    }

    private function renderView(array $errors = [], ?array $allUsers, ?int $groupId): void {
        $csrfToken = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;

        $view = new View("Group/invite_members_group.php", "front.php");
        $view->addData("csrfToken", $csrfToken);
        $view->addData("errors", $errors);
        $view->addData("users", $allUsers);
        $view->addData("groupId", $groupId);
        echo $view->render();
    }


    //retrieve the group id
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