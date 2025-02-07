<?php

namespace App\Services;

use App\Repositories\PhotoRepository;
use App\Repositories\UserRepository;
use App\Models\Photo;

class PhotoService {
    private PhotoRepository $photoRepository;
    private UserRepository $userRepository;

    public function __construct(PhotoRepository $photoRepository, UserRepository $userRepository) {
        $this->photoRepository = $photoRepository;
        $this->userRepository = $userRepository;
    }

    public function fetchPhotosByGroupId(int $groupId): array {
        $photos = $this->photoRepository->findByGroupId($groupId);
        
        foreach ($photos as $photo) {
            $user = $this->userRepository->findOneById($photo->getUserId());
            if ($user) {
                $photo->userFullName = $user->getFirstname() . ' ' . $user->getLastname();
            } else {
                $photo->userFullName = "Utilisateur Inconnu";
            }
        }

        return $photos;
    }
}