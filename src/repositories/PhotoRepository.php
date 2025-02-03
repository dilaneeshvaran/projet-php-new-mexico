<?php

namespace App\Repositories;

use App\Core\SQL;
use App\Models\Photo;

class PhotoRepository {
    private SQL $db;

    public function __construct(SQL $database) {
        $this->db = $database;
    }

    public function save(Photo $photo): bool
{
    $sql = "INSERT INTO photos (filename, original_name, description, title, mime_type, size, group_id, user_id)
            VALUES (:filename, :original_name, :description, :title, :mime_type, :size, :group_id, :user_id)";
    
    return $this->db->executePrepared($sql, [
        'filename' => $photo->getFilename(),
        'original_name' => $photo->getOriginalName(),
        'description' => $photo->getDescription(),
        'title' => $photo->getTitle(),
        'mime_type' => $photo->getMimeType(),
        'size' => $photo->getSize(),
        'group_id' => $photo->getGroupId(),
        'user_id' => $photo->getUserId(),
    ]);
}


public function findByGroupId(int $groupId): array {
    $sql = "SELECT * FROM photos WHERE group_id = :group_id ORDER BY created_at DESC";
    $rows = $this->db->queryPrepared($sql, ['group_id' => $groupId]);
    
    $photos = [];
    foreach ($rows as $row) {
        $photo = new Photo();
        $photo->setId($row['id']);
        $photo->setFilename($row['filename']);
        $photo->setOriginalName($row['original_name']);
        $photo->setDescription($row['description'] ?? '');
        $photo->setTitle($row['title'] ?? '');
        $photo->setMimeType($row['mime_type']);
        $photo->setSize($row['size']);
        $photo->setGroupId($row['group_id']);
        $photo->setUserId($row['user_id']);
        $photo->setCreatedAt($row['created_at']);
        $photos[] = $photo;
    }
    return $photos;
}

public function findById(int $photoId): ?Photo {
    $sql = "SELECT * FROM photos WHERE id = :id";
    $rows = $this->db->queryPrepared($sql, ['id' => $photoId]);

    if (empty($rows)) {
        return null; 
    }

    $row = $rows[0]; //fetch first row


    $photo = new Photo();
    $photo->setId($row['id'] ?? null);
    $photo->setFilename($row['filename'] ?? null);
    $photo->setOriginalName($row['original_name'] ?? null);
    $photo->setDescription($row['description'] ?? null);
    $photo->setTitle($row['title'] ?? null);
    $photo->setMimeType($row['mime_type'] ?? null);
    $photo->setSize($row['size'] ?? null);
    $photo->setGroupId($row['group_id'] ?? null);
    $photo->setUserId($row['user_id'] ?? null);
    $photo->setCreatedAt($row['created_at'] ?? null);

    return $photo;
}

public function delete(int $photoId): bool {
    $sql = "DELETE FROM photos WHERE id = :id";
    return $this->db->executePrepared($sql, ['id' => $photoId]);
}

}