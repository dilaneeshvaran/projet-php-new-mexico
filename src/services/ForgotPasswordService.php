<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\ResetPasswordTokenRepository;
use App\Requests\ForgotPasswordRequest;
use App\Core\Mailer;
use App\Core\MailerResend;

class ForgotPasswordService
{
    private UserRepository $userRepository;
    private ResetPasswordTokenRepository $resetPasswordTokenRepository;
    public function __construct(UserRepository $userRepository, ResetPasswordTokenRepository $resetPasswordTokenRepository)
    {
        $this->userRepository = $userRepository;
        $this->resetPasswordTokenRepository = $resetPasswordTokenRepository;
    }

    public function handleForgotPassword(string $email): void
    {
        //using ForgotPasswordRequest for sanitization
        $forgotPasswordRequest = new ForgotPasswordRequest($email);
        if (!$forgotPasswordRequest->isValid()) {
            throw new \InvalidArgumentException("Email invalid.");
        }
        $email = $forgotPasswordRequest->email;

        $user = $this->userRepository->findOneByEmail($email);
        //retourner au success si l'utilisateur n'existe pas pour éviter les attaques
        if (!$user) {
            return;
        }
        else{
            $token = bin2hex(random_bytes(32));
            $expiresAt = (new \DateTime())->modify('+1 hour');

            $this->resetPasswordTokenRepository->saveResetToken($email, $token, $expiresAt);

            $resetLink = "http://localhost:8000/reset-password/{$token}";
            Mailer::send($email, "Reinitialisation de votre mot de passe", "Voici le lien pour réinitialiser votre mot de passe: {$resetLink}");
            
            //Resend Mailer is not used because the project's php version is not compatible with the Resend library.
            //MailerResend::send($email, "Reinitialisation de votre mot de passe", "Voici le lien pour réinitialiser votre mot de passe: {$resetLink}");
        }
    }
}