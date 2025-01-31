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
}