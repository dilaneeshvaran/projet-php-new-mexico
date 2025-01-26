<?php

namespace App\Services;

use App\Repositories\ResetPasswordTokenRepository;
use App\Repositories\UserRepository;
use App\Requests\ResetPasswordRequest ;
use App\Models\UserValidator;

class ResetPasswordService
{
    private ResetPasswordTokenRepository $resetPasswordTokenRepository;
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository, ResetPasswordTokenRepository $resetPasswordTokenRepository)
    {
        $this->userRepository = $userRepository;
        $this->resetPasswordTokenRepository = $resetPasswordTokenRepository;
    }

    public function resetPassword(string $token, string $password, string $passwordConfirm): array
    {
        //sanitize
        $resetPasswordRequest = new ResetPasswordRequest($password, $passwordConfirm);
        $password = $resetPasswordRequest->password;
        $passwordConfirm = $resetPasswordRequest->passwordConfirm;

        //validate
        $userValidator = new UserValidator(null,$password, $passwordConfirm);
        $errors = $userValidator->getErrors();

        //returns errors if any are found
        if (!empty($errors)) {
            return $errors;
        }

        $reset = $this->resetPasswordTokenRepository->findToken($token);

        if (!$reset || new \DateTime() > new \DateTime($reset['expires_at'])) {
            throw new \Exception("Le lien de réinitialisation a expiré.");
        }

        $email = $reset['email'];
        $password = password_hash($password, PASSWORD_DEFAULT);
        $resetSuccessful = $this->userRepository->resetPassword($email, $password);
        if ($resetSuccessful) {
            $this->resetPasswordTokenRepository->deleteToken($token);
        } else {
            throw new \Exception("Erreur lors de la réinitalisation de votre mdp.");
        }

        return $errors;
    }
}
