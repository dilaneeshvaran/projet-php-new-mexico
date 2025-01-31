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
            SELECT g.id, g.name, g.description, g.created_at, ug.role, ug.group_access, ug.joined_at
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
            $userGroup->setGroupAccess($result['group_access']);

            $groups[] = ['group' => $group, 'userGroup' => $userGroup];
        }

        return $groups;
    }

    public function adminCreateGroup(UserGroup $userGroup): bool
    {
        $query = "INSERT INTO user_groups (user_id, group_id, role, group_access, joined_at) VALUES (:user_id, :group_id, :role, :group_access, :joined_at)";
        return $this->db->executePrepared($query, [
            'user_id'   => $userGroup->getUserId(),
            'group_id'  => $userGroup->getGroupId(),
            'role'      => $userGroup->getRole(),
            'joined_at' => $userGroup->getJoinedAt(),
            'group_access' => $userGroup->getGroupAccess(),
        ]);
    }

    public function getUsersByGroupId(int $groupId): array
{
    $sql = "
        SELECT u.id, u.firstname, u.lastname, u.email, 
               ug.group_access, ug.role, ug.joined_at
        FROM user_groups ug
        JOIN users u ON ug.user_id = u.id
        WHERE ug.group_id = :groupId
    ";

    $params = ['groupId' => $groupId];
    return $this->db->queryPrepared($sql, $params);
}

public function getGroupMemberById(int $groupId, int $memberId): ?array
{
    $query = "
        SELECT u.firstname, u.lastname, u.email, 
               ug.role, ug.group_access, ug.joined_at
        FROM user_groups ug
        JOIN users u ON ug.user_id = u.id
        WHERE ug.group_id = :group_id AND u.id = :member_id
    ";

    $params = ['group_id' => $groupId, 'member_id' => $memberId];
    $result = $this->db->queryPrepared($query, $params);
    
    return $result ? $result[0] : null;
}
public function getUserRole(int $groupId, int $userId): ?string
{
    $query = "SELECT role FROM user_groups WHERE group_id = :group_id AND user_id = :user_id";
    $result = $this->db->queryPrepared($query, ['group_id' => $groupId, 'user_id' => $userId]);
    return $result[0]['role'] ?? null;
}
public function updateGroupAccess(int $groupId, int $userId, string $access): bool
{
    $query = "UPDATE user_groups SET group_access = :access WHERE group_id = :group_id AND user_id = :user_id";
    return $this->db->executePrepared($query, [
        'access' => $access,
        'group_id' => $groupId,
        'user_id' => $userId
    ]);
}

public function deleteUserFromGroup(int $groupId, int $userId): bool
{
    $query = "DELETE FROM user_groups WHERE group_id = :group_id AND user_id = :user_id";
    return $this->db->executePrepared($query, ['group_id' => $groupId, 'user_id' => $userId]);
}
}