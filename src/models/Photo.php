<?php

namespace App\Models;

class Photo {
    private int $id;
    private string $filename;
    private string $originalName;
    private string $mimeType;
    private int $size;
    private int $groupId;
    private int $userId;
    private string $createdAt;

    public function getId(): int { return $this->id; }
    public function getFilename(): string { return $this->filename; }
    public function getOriginalName(): string { return $this->originalName; }
    public function getMimeType(): string { return $this->mimeType; }
    public function getSize(): int { return $this->size; }
    public function getGroupId(): int { return $this->groupId; }
    public function getUserId(): int { return $this->userId; }
    public function getCreatedAt(): string { return $this->createdAt; }

    public function setId(int $id): void { $this->id = $id; }
    public function setFilename(string $filename): void { $this->filename = $filename; }
    public function setOriginalName(string $originalName): void { $this->originalName = $originalName; }
    public function setMimeType(string $mimeType): void { $this->mimeType = $mimeType; }
    public function setSize(int $size): void { $this->size = $size; }
    public function setGroupId(int $groupId): void { $this->groupId = $groupId; }
    public function setUserId(int $userId): void { $this->userId = $userId; }
    public function setCreatedAt(string $createdAt): void { $this->createdAt = $createdAt; }
}