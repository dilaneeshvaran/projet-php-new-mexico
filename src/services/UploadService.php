<?php

namespace App\Services;

use App\Models\Photo;
use App\Models\PhotoValidator;
use App\Repositories\PhotoRepository;
use App\Repositories\UserGroupRepository;

class UploadService
{
    private UserGroupRepository $userGroupRepository;

    private PhotoRepository $photoRepository;

    public function __construct(PhotoRepository $photoRepository, UserGroupRepository $userGroupRepository)
    {
        $this->photoRepository = $photoRepository;
        $this->userGroupRepository = $userGroupRepository;
    }

    public function uploadPhoto(array $fileData, ?int $groupId, int $userId): array
    {
        $errors = [];

        //validate group_id
        $groupId = filter_var($groupId, FILTER_VALIDATE_INT);
        if ($groupId === null || !$this->userGroupRepository->exists($groupId)) {
            $errors[] = 'Groupe invalide';
            return $errors;
        }

        $validator = new PhotoValidator($fileData);
        $errors = $validator->getErrors();

        if (!empty($errors)) {
            return $errors;
        }

        //generate unique filename
        $extension = pathinfo($fileData['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $uploadPath = __DIR__ . '/../../public/uploads/' . $filename;

        //create photo object
        $photo = new Photo();
        $photo->setFilename($filename);
        $photo->setOriginalName($fileData['name']);
        $photo->setMimeType($fileData['type']);
        $photo->setSize($fileData['size']);
        $photo->setGroupId($groupId);
        $photo->setUserId($userId);

        //move the file to local dir and save to the db
        if (move_uploaded_file($fileData['tmp_name'], $uploadPath)) {
            if ($this->photoRepository->save($photo)) {
                return [];
            }
        }

        return $errors;
    }
}
