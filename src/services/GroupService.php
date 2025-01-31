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

    
}