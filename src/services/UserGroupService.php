<?php

namespace App\Services;

use App\Repositories\UserGroupRepository;
use App\Models\UserGroup;

class UserGroupService
{
    private UserGroupRepository $userGroupRepository;

    public function __construct(UserGroupRepository $userGroupRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
    }

    public function getUsersByGroupId(int $groupId): array
    {
        return $this->userGroupRepository->getUsersByGroupId($groupId);
    }

    public function getUserRole(int $groupId, int $userId): ?string
    {
        return $this->userGroupRepository->getUserRole($groupId, $userId);
    }

    public function getGroupAccess(int $groupId, int $userId): ?string
    {
        return $this->userGroupRepository->getGroupAccess($groupId, $userId);
    }
    
    public function updateMemberAccess(int $groupId, int $memberId, string $access): bool
    {
        return $this->userGroupRepository->updateGroupAccess($groupId, $memberId, $access);
    }
    
    public function removeMemberFromGroup(int $groupId, int $memberId): bool
    {
        return $this->userGroupRepository->deleteUserFromGroup($groupId, $memberId);
    }

    public function adminCreateGroup(int $userId, int $groupId): array
    {        
        $errors = [];

        if (empty($userId) || empty($groupId)) {
            $errors[] = "Erreur avec votre session.";
            return $errors;
        }

        $groupId = filter_var($groupId, FILTER_VALIDATE_INT);
        $userId = filter_var($userId, FILTER_VALIDATE_INT);

        if (!$groupId || !$this->userGroupRepository->exists($groupId)) {
            $errors[] = 'Groupe invalide';
            return $errors;
        }

        $userGroup = new UserGroup();
        $userGroup->setUserId($userId);
        $userGroup->setGroupId($groupId);
        $userGroup->setRole('admin');
        $userGroup->setJoinedAt(date('Y-m-d H:i:s'));
        $userGroup->setGroupAccess('writer');
        
        if (!$this->userGroupRepository->adminCreateGroup($userGroup)) {
            $errors[] = "Une erreur est survenue lors de la finalisation de création de votre groupe.";
        }

        return $errors;
    }

    public function getGroupMemberDetails(int $groupId, int $memberId): ?array
{
    return $this->userGroupRepository->getGroupMemberById($groupId, $memberId);
}
}
