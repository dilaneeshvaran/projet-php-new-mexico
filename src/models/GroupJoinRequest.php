<?php

namespace App\Models;

use App\Core\Model;

class GroupJoinRequest extends Model {
    protected int $id;
    protected int $user_id;
    protected int $group_id;
    protected string $status;
    protected string $created_at;

    public function getId(): int {
        return $this->id;
    }

    public function getUserId(): int {
        return $this->user_id;
    }

    public function getGroupId(): int {
        return $this->group_id;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setUserId(int $user_id): void {
        $this->user_id = $user_id;
    }

    public function setGroupId(int $group_id): void {
        $this->group_id = $group_id;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }

    public function setCreatedAt(string $created_at): void {
        $this->created_at = $created_at;
    }
}
