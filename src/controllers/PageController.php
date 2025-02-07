<?php

namespace App\Controllers;

use App\Core\View;
use App\Repositories\PageRepository;
use App\Core\SQL;

class PageController
{
    private PageRepository $pageRepository;

    public function __construct()
    {
        $this->pageRepository = new PageRepository(new SQL());
    }

    public function show(): void
    {
        $pageId = $_GET["id"] ?? 3;
        $result = $this->pageRepository->findOneById($pageId);
        if ($result) {
            $view = new View("page/show.php");
            $view->addData("content", $result->getContent());
            $view->addData("title", $result->getTitle());
            $view->addData("description", $result->getDescription());
            $view->addData("created", $result->getDateCreated());
            $view->render();
        } else {
            //Page non trouvé
            echo "Page introuvable.";
        }
    }
}