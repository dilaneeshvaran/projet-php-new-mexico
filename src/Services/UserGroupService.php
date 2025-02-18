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

public function joinGroup(int $groupId, int $userId): array
{
    $errors = [];
    
    //get the access type of the group
    $accessType = $this->userGroupRepository->getGroupAccessType($groupId);

    if (!$accessType) {
        $errors[] = "Groupe introuvable.";
        return $errors;
    }

    if ($accessType === 'open') {
        //directly join the group
        $userGroup = new UserGroup();
        $userGroup->setUserId($userId);
        $userGroup->setGroupId($groupId);
        $userGroup->setRole('member');
        $userGroup->setJoinedAt(date('Y-m-d H:i:s'));
        $userGroup->setGroupAccess('reader');

        if (!$this->userGroupRepository->adminCreateGroup($userGroup)) {
            $errors[] = "Erreur lors de l'adhesion au groupe.";
        }
    } elseif ($accessType === 'on_invitation') {
        //check if there's already a pending request
        if ($this->userGroupRepository->hasPendingJoinRequest($groupId, $userId)) {
            $errors[] = "Votre demande est deja en attente.";
        } else {
            //create join request in db
            if (!$this->userGroupRepository->addJoinRequest($groupId, $userId)) {
                $errors[] = "Erreur lors de la demande d adhésion.";
            } else {
                $errors[] = "Votre demande a ete envoyee.";
            }
        }
    } else {
        $errors[] = "Vous ne pouvez pas rejoindre ce groupe.";
    }

    return $errors;
}

public function leaveGroup(int $groupId, int $userId): bool
{
    return $this->userGroupRepository->leaveGroup($groupId, $userId);
}
}