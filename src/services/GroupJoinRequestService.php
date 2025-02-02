<?php

namespace App\Services;

use App\Repositories\GroupJoinRequestRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserGroupRepository;
use App\Models\UserGroup;

class GroupJoinRequestService {
    private GroupJoinRequestRepository $groupJoinRequestRepository;
    private UserRepository $userRepository;
    private UserGroupRepository $userGroupRepository;

    public function __construct(GroupJoinRequestRepository $groupJoinRequestRepository, UserRepository $userRepository, UserGroupRepository $userGroupRepository) {
        $this->groupJoinRequestRepository = $groupJoinRequestRepository;
        $this->userRepository = $userRepository;
        $this->userGroupRepository = $userGroupRepository;
    }

    public function getJoinRequestsByGroupId(int $groupId): array {
        $requests = $this->groupJoinRequestRepository->findByGroupId($groupId);
        foreach ($requests as &$request) {
            $user = $this->userRepository->findUserById($request['user_id']);
            if ($user) {
                $request['firstname'] = $user->getFirstname();
                $request['lastname'] = $user->getLastname();
                $request['email'] = $user->getEmail();
                $request['registered_on'] = $user->getCreatedAt();
            }
        }
        return $requests;
    }

    public function processRequest(int $requestId, string $status, int $groupId): bool {

        
        $request = $this->groupJoinRequestRepository->findById($requestId);
        if (!$request) {
            return false;
        }

//verifier si le groupe existe
$groupExists = $this->userGroupRepository->exists($groupId);
if (!$groupExists) {
    throw new \Exception($groupId."Le groupe spécifié n'existe pas.");
}

        if ($status === 'approved') {
            $userGroup = new UserGroup();
            $userGroup->setUserId($request['user_id']);
            $userGroup->setGroupId($groupId);
            $userGroup->setRole('member');
            $userGroup->setJoinedAt(date('Y-m-d H:i:s'));
            $userGroup->setGroupAccess('reader');
            $this->userGroupRepository->adminCreateGroup($userGroup);
        }
        
        return $this->groupJoinRequestRepository->updateStatus($requestId, $status);
    }
}
