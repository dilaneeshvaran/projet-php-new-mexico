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
    $sql = "INSERT INTO photos (filename, original_name, mime_type, size, group_id, user_id)
            VALUES (:filename, :original_name, :mime_type, :size, :group_id, :user_id)";
    
    return $this->db->executePrepared($sql, [
        'filename' => $photo->getFilename(),
        'original_name' => $photo->getOriginalName(),
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
        $photos[] = new Photo($row);
    }
    return $photos;
}
}