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
        //use RegisterRequest for sanitization
        $registerRequest = new RegisterRequest($data);

        //create the user
        $user = new User();
        $user->setFirstname($registerRequest->firstname);
        $user->setLastname($registerRequest->lastname);
        $user->setEmail($registerRequest->email);

        //validate the user
        $validator = new UserValidator($user, $registerRequest->password, $registerRequest->passwordConfirm);
        $errors = $validator->getErrors();


        //check if email already exists
        if ($this->userRepository->findOneByEmail($user->getEmail())) {
            $errors[] = "L'adresse e-mail est déjà utilisée.";
        }
        
        //returns errors if any are found
        if (!empty($errors)) {
            return $errors;
        }
        //hash the password
        $user->setPassword(password_hash($registerRequest->password, PASSWORD_DEFAULT));

        //save the user
        if (!$this->userRepository->save($user)) {
            throw new \Exception("Une erreur est survenue lors de l'inscription.");
        }
        
        return $errors;
    }
}
