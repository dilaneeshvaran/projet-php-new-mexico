<?php

namespace App\Requests;

class RegisterRequest
{
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $password;
    public string $passwordConfirm;

    public function __construct(array $data)
    {
        $this->firstname = ucwords(strtolower(trim($data["firstname"])));
        $this->lastname = strtoupper(trim($data["lastname"]));
        $this->email = filter_var(strtolower(trim($data["email"])), FILTER_SANITIZE_EMAIL);
        $this->password = trim($data["password"]);
        $this->passwordConfirm = trim($data["passwordConfirm"]);
    }
}