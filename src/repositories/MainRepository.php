<?php

namespace App\Repositories;

use App\Core\SQL;

class MainRepository
{
    private SQL $db;

    public function __construct(SQL $db)
    {
        $this->db = $db;
    }

    public function findOneById(int $id): ?array
    {
        $query = "SELECT id, title, description, content, date_created FROM page WHERE id = :id";
        $result = $this->db->queryPrepared($query, ['id' => $id]);

        return $result[0] ?? null;
    }
}