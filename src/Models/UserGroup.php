<?php

namespace App\Models;

class UserGroup
{
    private $user_id;
    private $group_id;
    private $role;
    private $group_access;
    private $joined_at;

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getGroupId()
    {
        return $this->group_id;
    }

    public function setGroupId($group_id)
    {
        $this->group_id = $group_id;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getJoinedAt()
    {
        return $this->joined_at;
    }

    public function setJoinedAt($joined_at)
    {
        $this->joined_at = $joined_at;
    }

    public function getGroupAccess()
    {
        return $this->group_access;
    }

    public function setGroupAccess($group_access)
    {
        $this->group_access = $group_access;
    }
}