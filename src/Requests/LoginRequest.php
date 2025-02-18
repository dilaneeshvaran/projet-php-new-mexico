<?php

namespace App\Requests;
class LoginRequest
{
    public string $email;
    public string $password;

    public function __construct(array $data)
    {
        $this->email = filter_var(strtolower(trim($data["email"] ?? '')), FILTER_SANITIZE_EMAIL);
        $this->password = trim($data["password"] ?? '');
    }

    public function isValid(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) && !empty($this->password);
    }
}