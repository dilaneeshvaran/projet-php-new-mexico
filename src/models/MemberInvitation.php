<?php

namespace App\Models;

class MemberInvitation
{
    private $id;
    private $memberId;
    private $groupId;
    private $sent_on;
    private $status;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getMemberId()
    {
        return $this->memberId;
    }

    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
    }

    public function getSentOn()
    {
        return $this->sent_on;
    }

    public function setSentOn($sent_on)
    {
        $this->sent_on = $sent_on;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}
