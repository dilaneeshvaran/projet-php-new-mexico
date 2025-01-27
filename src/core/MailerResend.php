<?php
namespace App\Core;

use Resend\Client;
use Resend\Resend;

// Not used because the project's php version is not compatible with the Resend library.
class MailerResend
{
    private Client $resend;

    public function __construct()
    {
        $this->resend = Resend::client(getenv('RESEND_API'));
    }

    public static function send(string $to, string $subject, string $message): void
    {
        $instance = new self();
        
        try {
            $instance->resend->emails->send([
                'from' => 'noreply@newmexico.com',
                'to' => [$to],
                'subject' => $subject,
                'html' => $message
            ]);
        } catch (\Exception $e) {
            throw new \Exception("Email n'a pas été envoyé: " . $e->getMessage());
        }
    }
}