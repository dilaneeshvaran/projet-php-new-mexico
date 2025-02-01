<?php

namespace App\Repositories;

use App\Core\SQL;

class GroupJoinRequestRepository {
    private SQL $db;

    public function __construct(SQL $db) {
        $this->db = $db;
    }

    public function findAll(): array {
        $query = "SELECT * FROM group_join_requests";
        return $this->db->queryPrepared($query);
    }

    public function findByGroupId(int $groupId): array {
        $query = "SELECT * FROM group_join_requests WHERE group_id = :group_id";
        return $this->db->queryPrepared($query, ['group_id' => $groupId]);
    }

    public function updateStatus(int $id, string $status): bool {
        $query = "UPDATE group_join_requests SET status = :status WHERE id = :id";
        return $this->db->executePrepared($query, [
            'status' => $status,
            'id' => $id
        ]);
    }

    public function findById(int $id): ?array {
        $query = "SELECT * FROM group_join_requests WHERE id = :id";
        $result = $this->db->queryPrepared($query, ['id' => $id]);
        return $result[0] ?? null;
    }
}
