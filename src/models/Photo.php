<?php

namespace App\Models;

class Photo {
    private int $id;
    private string $filename;
    private string $originalName;
    private string $title;
    private string $description;
    private string $mimeType;
    private int $size;
    private int $groupId;
    private int $userId;
    private string $createdAt;
    private bool $isPublic = false;
    private ?string $shareToken = null;

    // Getters
    public function getId(): int { return $this->id; }
    public function getFilename(): string { return $this->filename; }
    public function getOriginalName(): string { return $this->originalName; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getMimeType(): string { return $this->mimeType; }
    public function getSize(): int { return $this->size; }
    public function getGroupId(): int { return $this->groupId; }
    public function getUserId(): int { return $this->userId; }
    public function getCreatedAt(): string { return $this->createdAt; }
    public function isPublic(): bool { return $this->isPublic; }
    public function getShareToken(): ?string { return $this->shareToken; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setFilename(string $filename): void { $this->filename = $filename; }
    public function setOriginalName(string $originalName): void { $this->originalName = $originalName; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setMimeType(string $mimeType): void { $this->mimeType = $mimeType; }
    public function setSize(int $size): void { $this->size = $size; }
    public function setGroupId(int $groupId): void { $this->groupId = $groupId; }
    public function setUserId(int $userId): void { $this->userId = $userId; }
    public function setCreatedAt(string $createdAt): void { $this->createdAt = $createdAt; }
    public function setIsPublic(bool $isPublic): void { $this->isPublic = $isPublic; }
    public function setShareToken(?string $shareToken): void { $this->shareToken = $shareToken; }
}