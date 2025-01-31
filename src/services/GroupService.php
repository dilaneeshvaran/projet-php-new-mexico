<?php

namespace App\Services;

use App\Repositories\GroupRepository;
use App\Models\Group;

class GroupService {
    private GroupRepository $groupRepository;

    public function __construct(GroupRepository $groupRepository) {
        $this->groupRepository = $groupRepository;
    }

    public function getGroupById(int $id): ?Group {
        return $this->groupRepository->findById($id);
    }
}