<?php

namespace App\Services;

use App\Repositories\InviteMemberRepository;
use App\Repositories\UserGroupRepository;
use App\Repositories\UserRepository;
use App\Repositories\GroupRepository;
use App\Models\MemberInvitation;
use App\Models\UserGroup;

class InviteMemberService
{
    private InviteMemberRepository $inviteMemberRepository;
    private UserRepository $userRepository;
    private UserGroupRepository $userGroupRepository;
    private GroupRepository $groupRepository;

    public function __construct(InviteMemberRepository $inviteMemberRepository, UserRepository $userRepository, UserGroupRepository $userGroupRepository, GroupRepository $groupRepository)
    {
        $this->inviteMemberRepository = $inviteMemberRepository;
        $this->userRepository = $userRepository;
        $this->userGroupRepository = $userGroupRepository;
        $this->groupRepository = $groupRepository;
    }

    public function processInvitation(?int $groupId, ?int $memberId): array
    {
        $errors = [];

        //validate group_id
        $groupId = filter_var($groupId, FILTER_VALIDATE_INT);
        if ($groupId === null || !$this->userGroupRepository->exists($groupId)) {
            $errors[] = 'Groupe invalide';
            return $errors;
        }

        $memberId = filter_var($memberId, FILTER_VALIDATE_INT);
        if ($memberId === null || !$this->userRepository->exists($memberId)) {
            $errors[] = 'Utilisateur invalide';
            return $errors;
        }

        if ($this->inviteMemberRepository->hasPendingInvitation($groupId, $memberId)) {
            $errors[] = "Votre demande est deja en attente.";
            return $errors;
        }

        if ($this->userGroupRepository->isMember($groupId, $memberId)) {
            $errors[] = "L'utilisateur est déjà membre du groupe.";
            return $errors;
        }

        //create object
        $memberInvitation = new MemberInvitation();
        $memberInvitation->setGroupId($groupId);
        $memberInvitation->setMemberId($memberId);
        $memberInvitation->setStatus('pending');
        $memberInvitation->setSentOn((new \DateTime())->format('Y-m-d H:i:s'));

        //save to the db
        if (!$this->inviteMemberRepository->inviteMember($memberInvitation)) {
            $errors[] = "Échec de l'enregistrement de l'invitation";
        }
        return $errors;
    }

    public function respondInvitation(int $invitationId, int $userId, string $action): array
    {
        $errors = [];

        $invitation = $this->inviteMemberRepository->findById($invitationId);

        if (!$invitation) {
            $errors[] = "Invitation not found.";
            return $errors;
        }
        
        $groupId = $invitation['group_id'];

        if (!$invitation || (int)$invitation['member_id'] !== (int)$userId) {
            $errors[] = "Invalid invitation.";
            var_dump($invitation['member_id']);
            var_dump($userId);
            return $errors;
        }

        if ($action === 'accept') {
            $userGroup = new UserGroup();
            $userGroup->setUserId($userId);
            $userGroup->setGroupId($groupId);
            $userGroup->setRole('member');
            $userGroup->setGroupAccess('reader');
            $userGroup->setJoinedAt((new \DateTime())->format('Y-m-d H:i:s'));

            if (!$this->userGroupRepository->adminCreateGroup($userGroup)) {
                $errors[] = "Failed to add user to the group.";
                return $errors;
            }
        }

        if (!$this->inviteMemberRepository->updateStatus($invitationId, $action === 'accept' ? 'accepted' : 'declined')) {
            $errors[] = "Failed to update invitation status.";
        }

        return $errors;
    }

    public function getPendingInvitationsAndGroupDetails(int $userId): array
{
    $invitations = $this->inviteMemberRepository->getPendingInvitationsByUserId($userId);

    foreach ($invitations as &$invitation) {
        $group = $this->groupRepository->findById($invitation['group_id']);

        if ($group) {
            $invitation['group_name'] = $group->getName();
            $invitation['description'] = $group->getDescription();
        } else {
            $invitation['group_name'] = 'Unknown Group';
            $invitation['description'] = 'No description available';
        }
    }

    return $invitations;
}
}
