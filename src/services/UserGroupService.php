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
        
        if (!$this->userGroupRepository->adminCreateGroup($userGroup)) {
            $errors[] = "Une erreur est survenue lors de la finalisation de création de votre groupe.";
        }

        return $errors;
    }
}
