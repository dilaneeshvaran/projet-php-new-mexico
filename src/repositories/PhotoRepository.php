<?php

namespace App\Repositories;

use App\Core\SQL;
use App\Models\Photo;

class PhotoRepository {
    private SQL $db;

    public function __construct(SQL $database) {
        $this->db = $database;
    }

    public function save(Photo $photo): bool {
        $sql = "INSERT INTO photos (filename, original_name, description, title, mime_type, size, group_id, user_id, is_public, share_token)
                VALUES (:filename, :original_name, :description, :title, :mime_type, :size, :group_id, :user_id, :is_public, :share_token)";
        
        return $this->db->executePrepared($sql, [
            'filename' => $photo->getFilename(),
            'original_name' => $photo->getOriginalName(),
            'description' => $photo->getDescription(),
            'title' => $photo->getTitle(),
            'mime_type' => $photo->getMimeType(),
            'size' => $photo->getSize(),
            'group_id' => $photo->getGroupId(),
            'user_id' => $photo->getUserId(),
            'is_public' => $photo->isPublic(),
            'share_token' => $photo->getShareToken(),
        ]);
    }

    public function findByGroupId(int $groupId): array {
        $sql = "SELECT * FROM photos WHERE group_id = :group_id ORDER BY created_at DESC";
        $rows = $this->db->queryPrepared($sql, ['group_id' => $groupId]);
        
        $photos = [];
        foreach ($rows as $row) {
            $photos[] = $this->hydrate($row);
        }
        return $photos;
    }

    public function updateSharing(int $photoId, bool $isPublic, ?string $token): bool {
        return $this->db->executePrepared(
            "UPDATE photos SET is_public = ?, share_token = ? WHERE id = ?",
            [$isPublic, $token, $photoId]
        );
    }

    public function findByToken(string $token): ?Photo {
        $result = $this->db->queryPrepared(
            "SELECT * FROM photos WHERE share_token = ? AND is_public = TRUE LIMIT 1",
            [$token]
        );

        return $result ? $this->hydrate($result[0]) : null;
    }

    private function hydrate(array $data): Photo {
        $photo = new Photo();
        $photo->setId($data['id']);
        $photo->setFilename($data['filename']);
        $photo->setOriginalName($data['original_name']);
        $photo->setDescription($data['description'] ?? '');
        $photo->setTitle($data['title'] ?? '');
        $photo->setMimeType($data['mime_type']);
        $photo->setSize($data['size']);
        $photo->setGroupId($data['group_id']);
        $photo->setUserId($data['user_id']);
        $photo->setCreatedAt($data['created_at']);
        $photo->setIsPublic((bool)$data['is_public']);
        $photo->setShareToken($data['share_token']);

        return $photo;
    }
}
