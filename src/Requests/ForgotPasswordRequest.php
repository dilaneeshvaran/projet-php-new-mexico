<?php

namespace App\Requests;

class ForgotPasswordRequest
{
    public string $email;

    public function __construct(string $data)
    {
        $this->email = filter_var(strtolower(trim($data)), FILTER_SANITIZE_EMAIL);
    }

    public function isValid(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }
}