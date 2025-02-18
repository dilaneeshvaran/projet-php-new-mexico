<?php

namespace App\Services;

use App\Repositories\PhotoRepository;
use App\Repositories\UserGroupRepository;
use App\Models\Photo;

class DeletePhotoService
{
    private PhotoRepository $photoRepository;
    private UserGroupRepository $userGroupRepository;

    public function __construct(PhotoRepository $photoRepository, UserGroupRepository $userGroupRepository)
    {
        $this->photoRepository = $photoRepository;
        $this->userGroupRepository = $userGroupRepository;
    }

    public function deletePhoto(int $photoId, int $groupId, int $userId): array
    {
        $errors = [];
        
        //verify if photo belong to the group
        $photo = $this->photoRepository->findById($photoId);

        if (!$photo || $photo->getGroupId() !== $groupId) {
            $errors[] = 'Photo non trouvé !';
            return $errors;
        }

        //check if user is the creator of the photo or admin of the group
        $userRole = $this->userGroupRepository->getUserRole($groupId, $userId);
        if ($photo->getUserId() !== $userId && $userRole !== 'admin') {
            $errors[] = "Vous n'avez pas les droits !.";
            return $errors;
        }

        //remove photo file from the server
        $filePath = __DIR__ . '/../../public/uploads/' . $photo->getFilename();
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        //delete photo from database
        if (!$this->photoRepository->delete($photoId)) {
            $errors[] = 'Erreur : échec lors de la suppression de la photo.';
        }

        return $errors;
    }
}