<?php

namespace App\Repositories;

use App\Core\SQL;
use App\Models\Group;

class GroupRepository {
    private SQL $db;

    public function __construct(SQL $database) {
        $this->db = $database;
    }

    public function save(Group $group): bool {
        $sql = "INSERT INTO groups (name, description, created_at)
                VALUES (:name, :description, :created_at)";
        
        return $this->db->executePrepared($sql, [
            'name' => $group->getName(),
            'description' => $group->getDescription(),
            'created_at' => $group->getCreatedAt(),
        ]);
    }

    public function findById(int $id): ?Group {
        $sql = "SELECT * FROM groups WHERE id = :id";
        
        $rows = $this->db->queryPrepared($sql, ['id' => $id]);
        
        if (!empty($rows)) {
            $row = $rows[0];
            if (isset($row['id'], $row['name'], $row['description'], $row['created_at'])) {
                $group = new Group();
                $group->setId($row['id']);
                $group->setName($row['name']);
                $group->setDescription($row['description']);
                $group->setCreatedAt($row['created_at']);
                return $group;
            }
        }
        
        return null;
    }

    public function findAll(): array {
        $sql = "SELECT * FROM groups ORDER BY created_at DESC";
        
        $rows = $this->db->query($sql);
        
        $groups = [];
        foreach ($rows as $row) {
            $group = new Group();
            $group->setId($row['id']);
            $group->setName($row['name']);
            $group->setDescription($row['description']);
            $group->setCreatedAt($row['created_at']);
            $groups[] = $group;
        }
        
        return $groups;
    }
}