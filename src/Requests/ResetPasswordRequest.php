<?php

namespace App\Requests;

class ResetPasswordRequest
{
    public string $password;
    public string $passwordConfirm;

    public function __construct(string $password, string $pwdConfirm)
    {
        $this->password = trim($password);
        $this->passwordConfirm = trim($pwdConfirm);
    }
}