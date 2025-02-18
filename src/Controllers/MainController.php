<?php

namespace App\Controllers;

use App\Core\View;
use App\Repositories\MainRepository;
use App\Repositories\PageRepository;
use App\Repositories\UserGroupRepository;
use App\Core\SQL;
use App\Core\Session;
class MainController
{
    private ?string $_pseudo;
    private MainRepository $mainRepository;
    private PageRepository $pageRepository;
    private UserGroupRepository $userGroupRepository;

    public function __construct()
    {
        /*if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }*/
        $db = new SQL();
        $this->_pseudo = $_SESSION["firstname"] ?? null;
        $this->mainRepository = new MainRepository(new SQL());
        $this->pageRepository = new PageRepository(new SQL());
        $this->userGroupRepository = new UserGroupRepository($db);
    }

    public function home(): void
    {
        $session = new Session();
        if (!$session->isLogged()) {
            header('Location: /login');
            exit();
        }
        $pageId = 3;
        $userId = $_SESSION['user_id'] ?? null;
        $pageData = $this->pageRepository->findOneById($pageId);
        $view = new View("User/main.php", "front.php");

        $groups = [];
        if ($userId) {
            $userGroups = $this->userGroupRepository->getGroupsByUserId($userId);
            foreach ($userGroups as $groupData) {
                $group = $groupData['group'];
                $role = $this->userGroupRepository->getUserRole($group->getId(), $userId);
                $groups[] = [
                    'group' => $group,
                    'userGroup' => $groupData['userGroup'],
                    'isAdmin' => $role === 'admin'
                ];
            }
        }
        $view->addData("title", $pageData["title"] ?? "Accueil");
        $view->addData("description", $pageData["description"] ?? "Home");
        $view->addData("groups", $groups);
        // Render the view
        $view->render();
    }

    public function getPseudo(): string
    {
        return $this->_pseudo ?? 'Utilisateur non connecté';
    }
}