<?php

namespace App\Controllers;

use App\Core\View;
use App\Repositories\MainRepository;
use App\Repositories\PageRepository;
use App\Core\SQL;

class MainController
{
    private ?string $_pseudo;
    private MainRepository $mainRepository;
    private PageRepository $pageRepository;
    public function __construct()
    {
        /*if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }*/
        $this->_pseudo = $_SESSION["firstname"] ?? null;
        $this->mainRepository = new MainRepository(new SQL());
        $this->pageRepository = new PageRepository(new SQL());
    }

    public function home(): void
    {
        $pageId = 3;
        $pageData = $this->pageRepository->findOneById($pageId);
        $view = new View("User/main.php", "front.php");
        $view->addData("title", $pageData["title"] ?? "Accueil");
        $view->addData("description", $pageData["description"] ?? "Home");
        // Render the view
        $view->render();
    }

    public function getPseudo(): string
    {
        return $this->_pseudo ?? 'Utilisateur non connecté';
    }
}