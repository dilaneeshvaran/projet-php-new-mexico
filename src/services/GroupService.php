<?php

namespace App\Services;

use App\Repositories\GroupRepository;
use App\Models\Group;
use App\Models\GroupValidator;

class GroupService {
    private GroupRepository $groupRepository;

    public function __construct(GroupRepository $groupRepository) {
        $this->groupRepository = $groupRepository;
    }

    public function getGroupById(int $id): ?Group {
        return $this->groupRepository->findById($id);
    }

    public function createGroup(array $data): array {
        //dont allow group with same name to be created
        if ($this->groupRepository->findByName($data['name'] ?? '')) {
            return ['errors' => ['Un groupe avec ce nom existe déjà.']];
        }

        $group = new Group();
        $group->setName($data['name'] ?? '');
        $group->setDescription($data['description'] ?? '');
        $group->setCreatedAt(date('Y-m-d H:i:s'));
        $group->setAccessType($data['access_type'] ?? 'open');

        $validator = new GroupValidator($group);
        $errors = $validator->getErrors();

        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $groupId = $this->groupRepository->save($group);
        if (!$groupId) {
            return ['errors' => ["Une erreur est survenue lors de la création de votre groupe."]];
        }

        return ['groupId' => $groupId, 'errors' => []];
    }

    public function updateGroup(int $groupId, array $data): array {
        $errors = [];

        $group = $this->groupRepository->findById($groupId);
        if (!$group) {
            return ['Groupe non trouvé.'];
        }

        $group->setName($data['group_name'] ?? $group->getName());
        $group->setDescription($data['description'] ?? $group->getDescription());
        $group->setAccessType($data['access_type'] ?? $group->getAccessType());

        $validator = new GroupValidator($group);
        $errors = $validator->getErrors();

        if (!empty($errors)) {
            return $errors;
        }

        if (!$this->groupRepository->update($group)) {
            $errors[] = "ERREUR - lors de la mise à jour dans la bdd.";
        }

        return $errors;
    }

    public function deleteGroup(int $groupId): array {
        $errors = [];

        $group = $this->groupRepository->findById($groupId);
        if (!$group) {
            return ['Groupe non trouvé.'];
        }

        if (!$this->groupRepository->delete($group->getId())) {
            $errors[] = "ERREUR - lors de la suppression dans la bdd.";
        }

        return $errors;
    }
}