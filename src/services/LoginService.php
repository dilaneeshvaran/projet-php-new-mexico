<?php

namespace App\Services;

use App\Models\User;
use App\Requests\LoginRequest;
use App\Repositories\UserRepository;

class LoginService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loginUser(array $data): void
    {
        //use LoginRequest for sanitization
        $loginRequest = new LoginRequest($data);

        if (!$loginRequest->isValid()) {
            throw new \InvalidArgumentException("Email or password is invalid.");
        }

        $email = $loginRequest->email;
        $password = $loginRequest->password;

        $user = $this->userRepository->findOneByEmail($email);

        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new \Exception("L'adresse email ou le mot de passe sont incorrects.");
        }

        $_SESSION["log"] = true;
        $_SESSION["firstname"] = $user->getFirstname();
    }
}
