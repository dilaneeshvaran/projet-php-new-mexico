<?php

namespace App\Requests;


class UploadPhotoRequest
{
    public string $groupId;

    public function __construct(int $groupId)
    {
        $this->groupId = filter_var($groupId, FILTER_VALIDATE_INT);
    }
}