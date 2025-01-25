<?php

namespace App\Services;

use App\Models\User;
use App\Requests\RegisterRequest;
use App\Models\UserValidator;
use App\Repositories\UserRepository;

class RegisterService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(array $data): array
    {
        $errors = [];

        //use RegisterRequest for sanitization
        $registerRequest = new RegisterRequest($data);

        //create the user
        $user = new User();
        $user->setFirstname($registerRequest->firstname);
        $user->setLastname($registerRequest->lastname);
        $user->setEmail($registerRequest->email);
        $password = $registerRequest->password;
        $pwdConfirm = $registerRequest->passwordConfirm;

        //validate the user
        $userValidator = new UserValidator($user, $password, $pwdConfirm);
        $validationErrors = $userValidator->getErrors();

        if (!empty($validationErrors)) {
            $errors = array_merge($errors, $validationErrors);
        }

        //check if email already exists
        $existingUser = $this->userRepository->findOneByEmail($user->getEmail());
        if ($existingUser) {
            $errors[] = "L'adresse e-mail est déjà utilisée.";
        }
        
        //returns errors if any are found
        if (!empty($errors)) {
            return $errors;
        }
        //hash the password
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));

        //save the user
        if (!$this->userRepository->save($user)) {
            throw new \Exception("Une erreur est survenue lors de l'inscription.");
        }
        
        return $errors;
    }
}
