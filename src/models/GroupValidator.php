<?php

namespace App\Models;

class GroupValidator
{
    private ?Group $group;
    private array $errors = [];

    public function __construct(?Group $group)
    {
        $this->group = $group;
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->group) {
            if (strlen($this->group->getName()) < 3) {
                $this->errors[] = "Le nom du groupe doit contenir au moins 3 caractères.";
            }
            if (strlen($this->group->getDescription()) < 10) {
                $this->errors[] = "La description du groupe doit contenir au moins 10 caractères.";
            }
        }
    }


    public function getErrors(): array
    {
        return $this->errors;
    }
}
