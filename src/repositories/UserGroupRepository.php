<?php

namespace App\Repositories;

use App\Core\SQL;
use App\Models\Group;
use App\Models\UserGroup;

class UserGroupRepository
{
    private SQL $db;

    public function __construct(SQL $db)
    {
        $this->db = $db;
    }

    public function exists(int $groupId): bool
    {
        $query = "
            SELECT EXISTS(
                SELECT 1 
                FROM groups 
                WHERE id = :group_id
            ) as group_exists
        ";
        
        $result = $this->db->queryPrepared($query, ['group_id' => $groupId]);
        return (bool)$result[0]['group_exists'];
    }

    public function getGroupsByUserId(int $userId): array
    {
        $query = "
            SELECT g.id, g.name, g.description, g.created_at, ug.role, ug.joined_at
            FROM groups g
            JOIN user_groups ug ON g.id = ug.group_id
            WHERE ug.user_id = :user_id
        ";
        $results = $this->db->queryPrepared($query, ['user_id' => $userId]);

        $groups = [];
        foreach ($results as $result) {
            $group = new Group();
            $group->setId($result['id']);
            $group->setName($result['name']);
            $group->setDescription($result['description']);
            $group->setCreatedAt($result['created_at']);

            $userGroup = new UserGroup();
            $userGroup->setUserId($userId);
            $userGroup->setGroupId($result['id']);
            $userGroup->setRole($result['role']);
            $userGroup->setJoinedAt($result['joined_at']);

            $groups[] = ['group' => $group, 'userGroup' => $userGroup];
        }

        return $groups;
    }

    public function adminCreateGroup(UserGroup $userGroup): bool
    {
        $query = "INSERT INTO user_groups (user_id, group_id, role, joined_at) VALUES (:user_id, :group_id, :role, :joined_at)";
        return $this->db->executePrepared($query, [
            'user_id'   => $userGroup->getUserId(),
            'group_id'  => $userGroup->getGroupId(),
            'role'      => $userGroup->getRole(),
            'joined_at' => $userGroup->getJoinedAt(),
        ]);
    }
}