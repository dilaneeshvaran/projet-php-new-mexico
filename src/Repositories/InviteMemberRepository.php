<?php

namespace App\Repositories;

use App\Core\SQL;
use App\Models\MemberInvitation;

class InviteMemberRepository {
    private SQL $db;

    public function __construct(SQL $database) {
        $this->db = $database;
    }

    public function findAll(): array {
        $query = "SELECT * FROM member_invitations";
        return $this->db->queryPrepared($query);
    }

    public function inviteMember(MemberInvitation $memberInvitation): bool {
        $query = "INSERT INTO member_invitations (group_id, member_id, status, sent_on) VALUES (:group_id, :member_id, :status, :sent_on)";
        $params = [
            'group_id' => $memberInvitation->getGroupId(),
            'member_id' => $memberInvitation->getMemberId(),
            'status' => $memberInvitation->getStatus(),
            'sent_on' => $memberInvitation->getSentOn()
        ];
    
        return $this->db->executePrepared($query, $params);
    }

    //utilisateur consult les invitations reçues
    public function getInvitationsByUserId(int $userId): array {
        $query = "SELECT * FROM member_invitations WHERE member_id = :member_id";
        $params = ['member_id' => $userId];

        return $this->db->queryPrepared($query, $params);
    }

    //utilisateur accepte ou refuse une invitation
    public function updateStatus(int $id, string $status): bool {
        $query = "UPDATE member_invitations SET status = :status WHERE id = :id";
        return $this->db->executePrepared($query, [
            'status' => $status,
            'id' => $id
        ]);
    }

    //admin consult les invitations envoyées
    public function getInvitationsByGroupId(int $groupId): array {
        $query = "SELECT * FROM member_invitations WHERE group_id = :group_id";
        $params = ['group_id' => $groupId];

        return $this->db->queryPrepared($query, $params);
    }

    //verifie si un utilisateur a déjà été invité
    public function hasPendingInvitation(int $groupId, int $userId): bool {
        $query = "SELECT * FROM member_invitations WHERE group_id = :group_id AND member_id = :member_id AND status = 'pending'";
        $params = ['group_id' => $groupId, 'member_id' => $userId];
    
        $result = $this->db->queryPrepared($query, $params);
        return !empty($result);
    }

    public function findById(int $id): ?array {
        $query = "SELECT * FROM member_invitations WHERE id = :id";
        $result = $this->db->queryPrepared($query, ['id' => $id]);
        return $result[0] ?? null;
    }

    public function findInvitationStatus(int $groupId, int $userId): ?array {
    $query = "SELECT * FROM member_invitations WHERE group_id = :group_id AND member_id = :member_id ORDER BY sent_on DESC LIMIT 1";
    $params = ['group_id' => $groupId, 'member_id' => $userId];

    $result = $this->db->queryPrepared($query, $params);
    return $result[0] ?? null;
}

public function getPendingInvitationsByUserId(int $userId): array {
    $sql = "
        SELECT mi.id, mi.group_id, mi.member_id, mi.sent_on, mi.status , g.name AS group_name, g.description 
        FROM member_invitations mi
        JOIN groups g ON mi.group_id = g.id
        WHERE mi.member_id = :member_id AND mi.status = 'pending'
    ";
    return $this->db->queryPrepared($sql, ['member_id' => $userId]);
}

public function findLatestInvitationStatus(int $groupId, int $userId): ?array {
    $query = "SELECT * FROM member_invitations 
             WHERE group_id = :group_id 
             AND member_id = :member_id 
             ORDER BY sent_on DESC 
             LIMIT 1";
             
    $params = [
        'group_id' => $groupId,
        'member_id' => $userId
    ];
    
    $result = $this->db->queryPrepared($query, $params);
    return !empty($result) ? $result[0] : null;
}

}