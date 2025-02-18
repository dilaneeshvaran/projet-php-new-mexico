<?php

namespace App\Models;

class Page
{
    private int $id;
    private string $title;
    private string $description;
    private string $content;
    private string $dateCreated;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = trim($title);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = trim($description);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = trim($content);
    }

    public function getDateCreated(): string
    {
        return $this->dateCreated;
    }

    public function setDateCreated(string $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }
}
