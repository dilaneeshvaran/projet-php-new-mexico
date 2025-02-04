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

    /**
     * Récupère les photos d'un groupe et ajoute les noms des utilisateurs.
     */
    public function fetchPhotosByGroupId(int $groupId): array {
        $photos = $this->photoRepository->findByGroupId($groupId);
        
        foreach ($photos as $photo) {
            $user = $this->userRepository->findOneById($photo->getUserId());
            $photo->userFullName = $user ? $user->getFirstname() . ' ' . $user->getLastname() : "Unknown User";
        }

        return $photos;
    }

    /**
     * Génère un token unique pour le partage d'une photo.
     */
    public function generateShareToken(): string {
        return bin2hex(random_bytes(32));
    }

    /**
     * Vérifie si un utilisateur peut gérer une photo (propriétaire ou propriétaire du groupe).
     */
    public function canManage(Photo $photo, int $userId, int $groupId): bool {
        return $photo->getUserId() === $userId || $this->isGroupOwner($groupId, $userId);
    }

    /**
     * Vérifie si un utilisateur est propriétaire du groupe.
     * À implémenter selon la logique métier.
     */
    private function isGroupOwner(int $groupId, int $userId): bool {
        // Implémentez la logique de vérification du propriétaire du groupe
        // Ex: return $this->groupRepository->isOwner($groupId, $userId);
        return false; // Placeholder
    }
}
