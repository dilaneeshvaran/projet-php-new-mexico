<?php

namespace App\Models;

use App\Models\User;

class UserValidator {
    
    private ?User $user;
    private string $password;
    private string $pwdConfirm;
    private array $errors = [];

    public function __construct(?User $user, string $password, string $pwdConfirm){
        $this->user = $user;
        $this->password = $password;
        $this->pwdConfirm = $pwdConfirm;

        $this->validate();
    }

    private function validate(): void
    {
        if ($this->user) {
            if(strlen($this->user->getFirstname()) < 2){
                $this->errors[] = "Le prénom doit faire plus de 2 caractères";
            }
            if(strlen($this->user->getLastname()) < 2){
                $this->errors[] = "Le nom doit faire plus de 2 caractères";
            }
            if(!filter_var($this->user->getEmail(), FILTER_VALIDATE_EMAIL)){
                $this->errors[] = "L'email est invalide";
            }
        }
        
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $this->password)) {
            $this->errors[] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.";
        }
        if ($this->pwdConfirm !== $this->password) {
            $this->errors[] = "Le mot de passe de confirmation ne correspond pas";
        }
    }
    public function validateEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "L'email est invalide";
        }
    }

    public function validatePassword(string $password): void
    {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
            $this->errors[] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.";
        }
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}