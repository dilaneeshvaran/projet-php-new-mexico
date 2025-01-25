<?php

namespace App\Repositories;

use App\Core\SQL;
use App\Models\Page;

class PageRepository
{
    private SQL $db;

    public function __construct(SQL $db)
    {
        $this->db = $db;
    }

    public function findOneById(int $id): ?Page
    {
        $query = "SELECT id, title, description, content, date_created FROM page WHERE id = :id";
        $result = $this->db->queryPrepared($query, ['id' => $id]);

        if (empty($result)) {
            return null;
        }

        $pageData = $result[0];

        $page = new Page();
        $page->setId($pageData['id']);
        $page->setTitle($pageData['title'] ?? "Inscription");
        $page->setDescription($pageData['description'] ?? "Page d'inscription");
        $page->setContent($pageData['content'] ?? "");
        $page->setDateCreated($pageData['date_created']);

        return $page;
    }
}
